import pandas as pd
import numpy as np
from sqlalchemy import create_engine
from collections import defaultdict
import math

# Koneksi ke database
DATABASE_URI = 'mysql+mysqlconnector://root:@localhost/ta'
engine = create_engine(DATABASE_URI)

# Ambil data latih dari database
df_train = pd.read_sql('SELECT * FROM datalatihbungtowel', con=engine)

# Ambil data uji dari database
df_test = pd.read_sql('SELECT * FROM dataujibungtowel', con=engine)

# Tokenisasi teks
def tokenize(text):
    return text.lower().split()

# Menghitung Term Frequency (TF)
def compute_tf(text):
    tf_dict = defaultdict(int)
    tokens = tokenize(text)
    for token in tokens:
        tf_dict[token] += 1
    for token in tf_dict:
        tf_dict[token] /= len(tokens)
    return tf_dict

# Menghitung Inverse Document Frequency (IDF)
def compute_idf(documents):
    idf_dict = defaultdict(int)
    N = len(documents)
    for document in documents:
        tokens = set(tokenize(document))
        for token in tokens:
            idf_dict[token] += 1
    for token in idf_dict:
        idf_dict[token] = math.log(N / idf_dict[token])
    return idf_dict

# Menghitung TF-IDF untuk satu dokumen
def compute_tfidf(tf, idf):
    tfidf = {}
    for token, tf_val in tf.items():
        tfidf[token] = tf_val * idf.get(token, 0)
    return tfidf

# Menghitung TF-IDF untuk semua dokumen
def compute_tfidf_for_documents(documents):
    idf = compute_idf(documents)
    tfidf_list = []
    for document in documents:
        tf = compute_tf(document)
        tfidf = compute_tfidf(tf, idf)
        tfidf_list.append(tfidf)
    return tfidf_list, idf

# Membuat vektor dari hasil TF-IDF
def create_feature_matrix(tfidf_list, idf):
    tokens = sorted(idf.keys())
    feature_matrix = np.zeros((len(tfidf_list), len(tokens)))
    for i, tfidf in enumerate(tfidf_list):
        for j, token in enumerate(tokens):
            feature_matrix[i, j] = tfidf.get(token, 0)
    return feature_matrix, tokens

# Menghitung TF-IDF untuk data latih
tfidf_train_list, idf = compute_tfidf_for_documents(df_train['text'])
X_train_array, tokens = create_feature_matrix(tfidf_train_list, idf)

# Menghitung TF-IDF untuk data uji
tfidf_test_list, _ = compute_tfidf_for_documents(df_test['text'])
X_test_array, _ = create_feature_matrix(tfidf_test_list, idf)

# Implementasi Naive Bayes tanpa library
class NaiveBayesClassifier:
    def __init__(self):
        self.class_priors = {}
        self.feature_probs = {}

    def fit(self, X, y):
        n_samples, n_features = X.shape
        self.classes = np.unique(y)
        
        for cls in self.classes:
            X_cls = X[y == cls]
            self.class_priors[cls] = X_cls.shape[0] / n_samples
            self.feature_probs[cls] = (X_cls.sum(axis=0) + 1) / (X_cls.sum() + n_features)  # Laplace smoothing

    def predict(self, X):
        y_pred = []
        for x in X:
            class_probs = {}
            for cls in self.classes:
                prior = np.log(self.class_priors[cls])
                conditional = np.sum(np.log(self.feature_probs[cls]) * x)
                class_probs[cls] = prior + conditional
            y_pred.append(max(class_probs, key=class_probs.get))
        return np.array(y_pred)

# Inisialisasi model Naive Bayes
nb_model = NaiveBayesClassifier()

# Latih model Naive Bayes menggunakan data latih
nb_model.fit(X_train_array, df_train['label'])

# Lakukan prediksi menggunakan model Naive Bayes
nb_y_pred = nb_model.predict(X_test_array)

# Hitung jumlah prediksi yang benar
correct_predictions = (df_test['label'] == nb_y_pred).sum()

# Hitung total prediksi
total_predictions = len(df_test)

# Hitung akurasi
manual_accuracy = correct_predictions / total_predictions

print("Akurasi Naive Bayes (Manual):", manual_accuracy)

# Menghitung metrik-metrik evaluasi secara manual
def manual_classification_report(true_labels, predicted_labels):
    # Mendefinisikan kelas yang unik
    classes = np.unique(true_labels)
    
    # Membuat dictionary untuk menyimpan metrik-metrik untuk setiap kelas
    report = {'precision': {}, 'recall': {}, 'f1-score': {}}
    
    # Looping melalui setiap kelas
    for cls in classes:
        true_positive = ((true_labels == cls) & (predicted_labels == cls)).sum()
        false_positive = ((true_labels != cls) & (predicted_labels == cls)).sum()
        false_negative = ((true_labels == cls) & (predicted_labels != cls)).sum()
        
        # Menghitung presisi
        precision = true_positive / (true_positive + false_positive)
        
        # Menghitung recall
        recall = true_positive / (true_positive + false_negative)
        
        # Menghitung F1-score
        f1_score = 2 * (precision * recall) / (precision + recall)
        
        # Menyimpan metrik-metrik untuk kelas saat ini dalam dictionary
        report['precision'][cls] = precision
        report['recall'][cls] = recall
        report['f1-score'][cls] = f1_score
    
    # Menghitung rata-rata presisi, recall, dan F1-score untuk semua kelas
    avg_precision = np.mean(list(report['precision'].values()))
    avg_recall = np.mean(list(report['recall'].values()))
    avg_f1_score = np.mean(list(report['f1-score'].values()))
    
    # Menambahkan rata-rata metrik ke dalam dictionary
    report['precision']['avg'] = avg_precision
    report['recall']['avg'] = avg_recall
    report['f1-score']['avg'] = avg_f1_score
    
    return report

# Membuat laporan klasifikasi secara manual
manual_classification_rep = manual_classification_report(df_test['label'], nb_y_pred)

# Menampilkan laporan klasifikasi secara manual
print("Laporan Klasifikasi Naive Bayes (Manual):")
for metric in manual_classification_rep:
    print(metric.capitalize())
    for cls, value in manual_classification_rep[metric].items():
        print(f"  {cls}: {value}")
    print()
    
# Menghitung confusion matrix secara manual
def manual_confusion_matrix(true_labels, predicted_labels):
    # Mendefinisikan kelas yang unik
    classes = np.unique(true_labels)
    
    # Membuat confusion matrix berukuran (n_classes, n_classes)
    cm = np.zeros((len(classes), len(classes)), dtype=int)
    
    # Mengisi confusion matrix
    for true_label, predicted_label in zip(true_labels, predicted_labels):
        cm[true_label, predicted_label] += 1
    
    return cm

# Membuat confusion matrix secara manual
manual_cm = manual_confusion_matrix(df_test['label'], nb_y_pred)

# Menampilkan confusion matrix secara manual
print("Confusion Matrix Naive Bayes (Manual):")
for i in range(manual_cm.shape[0]):
    for j in range(manual_cm.shape[1]):
        print(manual_cm[i, j], end='\t')
    print()
    
# Menyiapkan data hasil pengujian
test_results = {
    'akurasi': [manual_accuracy],
    'precision_0': [manual_classification_rep['precision'][0]],
    'precision_1': [manual_classification_rep['precision'][1]],
    'avg_precision': [manual_classification_rep['precision']['avg']],    
    'recall_0': [manual_classification_rep['recall'][0]],
    'recall_1': [manual_classification_rep['recall'][1]],
    'avg_recall': [manual_classification_rep['recall']['avg']],
    'f1_score_0': [manual_classification_rep['f1-score'][0]],
    'f1_score_1': [manual_classification_rep['f1-score'][1]],
    'avg_f1_score': [manual_classification_rep['f1-score']['avg']],
    'confusion_matrix_00': [manual_cm[0, 0]],
    'confusion_matrix_01': [manual_cm[0, 1]],
    'confusion_matrix_10': [manual_cm[1, 0]],
    'confusion_matrix_11': [manual_cm[1, 1]]
}

# Membuat DataFrame dari data hasil pengujian
df_test_results = pd.DataFrame(test_results)

# Menyimpan DataFrame ke dalam tabel pengujian di database
table_name = 'pengujian'  # Ganti dengan nama tabel yang sesuai
df_test_results.to_sql(table_name, con=engine, if_exists='append', index=False)

print("Hasil pengujian berhasil disimpan ke dalam tabel:", table_name)
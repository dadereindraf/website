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

# Hitung True Positive (TP), True Negative (TN), False Positive (FP), False Negative (FN)
TP = np.sum((df_test['label'] == 1) & (nb_y_pred == 1))
TN = np.sum((df_test['label'] == 0) & (nb_y_pred == 0))
FP = np.sum((df_test['label'] == 0) & (nb_y_pred == 1))
FN = np.sum((df_test['label'] == 1) & (nb_y_pred == 0))

# Hitung akurasi
accuracy = (TP + TN) / (TP + TN + FP + FN)

# Hitung presisi
precision = TP / (TP + FP) if (TP + FP) != 0 else 0

# Hitung recall
recall = TP / (TP + FN) if (TP + FN) != 0 else 0

# Hitung F1-score
f1_score = 2 * (precision * recall) / (precision + recall) if (precision + recall) != 0 else 0

# Tampilkan hasil evaluasi
print("Akurasi:", accuracy)
print("Presisi:", precision)
print("Recall:", recall)
print("F1-score:", f1_score)

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
    'akurasi': [accuracy],
    'precision': [precision],
    'recall': [recall],
    'f1_score': [f1_score],
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

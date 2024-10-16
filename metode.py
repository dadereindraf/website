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
class NaiveBayes:
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
        y_prob = []
    
        for x in X:
            class_probs = {}
            for cls in self.classes:
                prior = self.class_priors[cls]
                likelihood_score = np.prod(self.feature_probs[cls] ** x)  # Menggunakan np.prod untuk perkalian elemen-wise
                class_probs[cls] = prior * likelihood_score
            
            # Normalisasi probabilitas
            total_prob = sum(class_probs.values())
            normalized_probs = {cls: prob / total_prob for cls, prob in class_probs.items()}
            
            normalized_probs = {cls: round(prob, 6) for cls, prob in normalized_probs.items()}
            
            y_prob.append(normalized_probs)
            y_pred.append(max(normalized_probs, key=normalized_probs.get))
        
        return np.array(y_pred), y_prob


# Inisialisasi model Naive Bayes
nb_model = NaiveBayes()

# Latih model Naive Bayes menggunakan data latih
nb_model.fit(X_train_array, df_train['label'])

# Lakukan prediksi menggunakan model Naive Bayes
nb_y_pred, nb_y_prob = nb_model.predict(X_test_array)

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

# Membuat confusion matrix dari TP, TN, FP, FN
confusion_matrix = np.array([[TN, FP],
                             [FN, TP]])

# Menyiapkan data hasil pengujian
test_results = {
    'akurasi': [accuracy],
    'precision': [precision],
    'recall': [recall],
    'f1_score': [f1_score],
    'confusion_matrix_00': [confusion_matrix[0, 0]],
    'confusion_matrix_01': [confusion_matrix[0, 1]],
    'confusion_matrix_10': [confusion_matrix[1, 0]],
    'confusion_matrix_11': [confusion_matrix[1, 1]]
}

# Membuat DataFrame dari data hasil pengujian
df_test_results = pd.DataFrame(test_results)

# Menyimpan DataFrame ke dalam tabel pengujian di database
table_name = 'pengujian'
df_test_results.to_sql(table_name, con=engine, if_exists='append', index=False)

print("Hasil pengujian berhasil disimpan ke dalam tabel:", table_name)

# Menyiapkan data probabilitas, aktual, dan prediksi
probs_data = {
    'actual': df_test['label'],
    'predicted': nb_y_pred,
    'prob_class_0': [prob[0] for prob in nb_y_prob],
    'prob_class_1': [prob[1] for prob in nb_y_prob]
}

# Membuat DataFrame dari data probabilitas
df_probs = pd.DataFrame(probs_data)

# Menyimpan DataFrame ke dalam tabel probs di database
probs_table_name = 'probs'
df_probs.to_sql(probs_table_name, con=engine, if_exists='append', index=False)

print("Hasil probabilitas berhasil disimpan ke dalam tabel:", probs_table_name)

# Menghitung jumlah prediksi untuk setiap kategori
totalHS = np.sum(nb_y_pred == 1)
totalNHS = np.sum(nb_y_pred == 0)

# Menyiapkan data total prediksi
total_predictions = {
    'totalHS': [totalHS],
    'totalNHS': [totalNHS]
}

# Membuat DataFrame dari data total prediksi
df_total_predictions = pd.DataFrame(total_predictions)

# Menyimpan DataFrame ke dalam tabel total_prediksi di database
total_predictions_table_name = 'modelling' 
df_total_predictions.to_sql(total_predictions_table_name, con=engine, if_exists='append', index=False)

print("Total prediksi berhasil disimpan ke dalam tabel:", total_predictions_table_name)
import pymysql
import pandas as pd
import math

# Membuat koneksi ke database
def connect_to_database():
    connection = pymysql.connect(host='localhost',
                                 user='root',
                                 password='',
                                 database='ta',
                                 charset='utf8mb4',
                                 cursorclass=pymysql.cursors.DictCursor)
    return connection

# Mendapatkan data dari database
def get_data_from_database(query):
    connection = connect_to_database()
    try:
        with connection.cursor() as cursor:
            cursor.execute(query)
            result = cursor.fetchall()
            df = pd.DataFrame(result)
            return df
    finally:
        connection.close()

# Fungsi untuk menghitung TF-IDF
def calculate_tfidf(data):
    # Membuat kamus untuk menyimpan frekuensi kemunculan kata dalam setiap dokumen
    tf = {}
    total_docs = len(data)
    for index, row in data.iterrows():
        tokens = row['text'].split()  # Split teks menjadi kata-kata
        for token in tokens:
            tf.setdefault(index, {}).setdefault(token, 0)
            tf[index][token] += 1

    # Menghitung IDF untuk setiap kata dalam semua dokumen
    idf = {}
    for doc in tf.values():
        for word in doc.keys():
            idf.setdefault(word, 0)
            idf[word] += 1

    for word, value in idf.items():
        idf[word] = math.log(total_docs / value)

    # Menghitung TF-IDF untuk setiap kata dalam setiap dokumen
    tfidf = {}
    for doc_id, doc in tf.items():
        tfidf[doc_id] = {}
        for word, freq in doc.items():
            tfidf[doc_id][word] = freq * idf[word]

    return tfidf

# Fungsi untuk menghitung Frekuensi Kemunculan Kata
def calculate_word_frequencies(data):
    word_freq = {}
    for doc_id, doc in data.items():
        for word, freq in doc.items():
            word_freq.setdefault(word, {'total': 0, 'doc_counts': {}})
            word_freq[word]['total'] += freq
            word_freq[word]['doc_counts'].setdefault(doc_id, 0)
            word_freq[word]['doc_counts'][doc_id] += 1
    return word_freq

# Contoh penggunaan
query_latih = "SELECT text, label FROM datalatihbungtowel"
df_latih = get_data_from_database(query_latih)

query_uji = "SELECT text, label FROM dataujibungtowel"
df_uji = get_data_from_database(query_uji)

# Hitung TF-IDF untuk data latih
tfidf_latih = calculate_tfidf(df_latih)

# Hitung TF-IDF untuk data uji
tfidf_uji = calculate_tfidf(df_uji)

# Hitung Frekuensi Kemunculan Kata untuk data latih
word_freq_latih = calculate_word_frequencies(tfidf_latih)

# Hitung Frekuensi Kemunculan Kata untuk data uji
word_freq_uji = calculate_word_frequencies(tfidf_uji)

# Hitung Probabilitas Kelas
total_docs_latih = len(tfidf_latih)
class_probabilities = {}
for doc_id, doc in tfidf_latih.items():
    label = df_latih.loc[doc_id, 'label']
    class_probabilities.setdefault(label, 0)
    class_probabilities[label] += 1

for label, count in class_probabilities.items():
    class_probabilities[label] = count / total_docs_latih

# Hitung Probabilitas Kondisional
conditional_probabilities = {}
for word, info in word_freq_latih.items():
    conditional_probabilities.setdefault(word, {})
    for label in class_probabilities.keys():
        label_count = sum(1 for doc_id, doc in tfidf_latih.items() if df_latih.loc[doc_id, 'label'] == label)
        word_count = info['doc_counts'].get(doc_id, 0)
        conditional_probabilities[word][label] = (word_count + 1) / (label_count + len(word_freq_latih))

# Prediksi Kelas untuk data uji
predictions = []
for doc_id, doc in tfidf_uji.items():
    max_prob = -1
    predicted_label = None
    for label in class_probabilities.keys():
        prob = math.log(class_probabilities[label])
        for word, freq in doc.items():
            if word in conditional_probabilities:
                prob += freq * math.log(conditional_probabilities[word].get(label, 1 / (label_count + len(word_freq_latih))))
        if prob > max_prob:
            max_prob = prob
            predicted_label = label
    predictions.append(predicted_label)

# Tampilkan prediksi
print("\nPrediksi Kelas untuk data uji:")
for i, pred in enumerate(predictions):
    print(f"Tweet {i}: Kelas Prediksi: {pred}")

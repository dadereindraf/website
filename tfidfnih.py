import pymysql.cursors
import pandas as pd
import nltk
import re
import math

# Koneksi ke database
connection = pymysql.connect(host='localhost',
                             user='root',
                             password='',
                             database='ta',
                             charset='utf8mb4',
                             cursorclass=pymysql.cursors.DictCursor)

# Fungsi perhitungan TF menggunakan phrase frequency
def term_frequency(text):
    tokens = nltk.word_tokenize(text)
    term_freqs = {}
    for token in tokens:
        if token in term_freqs:
            term_freqs[token] += 1 / len(tokens)
        else:
            term_freqs[token] = 1 / len(tokens)
    return term_freqs

# Fungsi untuk mengambil data dari tabel di database
def fetch_data(connection, table_name):
    query = f"SELECT text FROM {table_name}"
    with connection.cursor() as cursor:
        cursor.execute(query)
        result = cursor.fetchall()
    return result

# Ambil data dari tabel data latih dan data uji
data_latih = fetch_data(connection, 'datalatihbungtowel')
data_uji = fetch_data(connection, 'dataujibungtowel')

# Hitung TF untuk data latih
tf_latih = []
for row in data_latih:
    tf_latih.append(term_frequency(row['text']))

# Hitung TF untuk data uji
tf_uji = []
for row in data_uji:
    tf_uji.append(term_frequency(row['text']))

# Tampilkan hasil TF untuk data latih di terminal
print("Hasil Term Frequency (TF) untuk Data Latih:")
for i, tf in enumerate(tf_latih, 1):
    print(f"Tweet {i}: {tf}")

# Tampilkan hasil TF untuk data uji di terminal
print("\nHasil Term Frequency (TF) untuk Data Uji:")
for i, tf in enumerate(tf_uji, 1):
    print(f"Tweet {i}: {tf}")

# calculated document frequency
def document_frequency(docs):
    freqs = {}
    for doc in docs:
        for term in doc.keys():
            if term in freqs:
                freqs[term] += 1 / len(docs)
            else:
                freqs[term] = 1 / len(docs)
    return freqs

def tfidf_score(docs):
    doc_freqs = document_frequency(docs)
    for doc in docs:
        for term in doc.keys():
            doc[term] = doc[term] * math.log(len(docs)/doc_freqs[term], 2)
    return docs

# Hitung skor TF-IDF untuk data latih dan tampilkan
score_latih = tfidf_score(tf_latih)
print("\nHasil TF-IDF untuk Data Latih:")
for i, score in enumerate(score_latih, 1):
    print(f"Tweet {i}: {score}")

# Hitung skor TF-IDF untuk data uji dan tampilkan
score_uji = tfidf_score(tf_uji)
print("\nHasil TF-IDF untuk Data Uji:")
for i, score in enumerate(score_uji, 1):
    print(f"Tweet {i}: {score}")

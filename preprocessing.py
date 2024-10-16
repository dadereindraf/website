import pandas as pd
import re
import mysql.connector
from sqlalchemy import create_engine
from Sastrawi.Stemmer.StemmerFactory import StemmerFactory
import json

# Lakukan preprocessing atau manipulasi data sesuai kebutuhan
def clean_text(text):
    # Periksa apakah nilai adalah string
    if isinstance(text, str):
        # Menghapus teks yang mengandung 'https'
        text = re.sub(r'https\S+', '', text)

        # Menghapus mention
        text = re.sub(r'@\w+', '', text)

        # Menghapus angka
        text = re.sub(r'\d+', '', text)

        # Mengonversi semua teks ke huruf kecil (lowercase)
        text = text.lower()

        # Mengganti simbol dan tanda baca dengan spasi
        text = re.sub(r'[^\w\s]', ' ', text)
        
    return text

def tokenize_text(text):
    # Memisahkan teks menjadi token berdasarkan spasi
    tokens = text.split()
    return tokens

replacement_dict = {
    "anjg": "anjing",
    "ajg" : "anjing",
    "org" : "orang",
    "kemaren" : "kemarin",
    "bgst": "bangsat",
    "bgt": "sekali",
    "gw": "saya",
    "spt": "seperti",
    "gue": "saya",
    "gua" : "saya",
    "ajig": "anjing",
    "bwang": "bang",
    "trs": "terus",
    "ngmg": "ngomong",
    "skrng": "sekarang",
    "lu": "kamu",
    "lo": "kamu",
    "sbagai": "sebagai",
    "mnt": "minta",
    "u": "kamu",
    "atou": "atau",
    "bgt": "sekali",
    "klo": "kalau",
    "kl": "kalau",
    "dah": "sudah",
    "dtg": "datang",
    "mau": "ingin",
    "gimana": "bagaimana",
    "gmn": "bagaimana",
    "krn": "karena",
    "bkn": "bukan",
    "dlu": "dulu",
    "ngerti": "mengerti",
    "bgmna" : "bagaimana",
    "ngga": "tidak",
    "ya": "iya",
    "tp": "tapi",
    "kntl" : "kontol",
    "kontlo" : "kontol",
    "sama": "dengan",
    "ntr": "nanti",
    "tdk": "tidak",
    "jgn": "jangan",
    "deh": "",
    "klo": "kalau",
    "jg": "juga",
    "gt": "begitu",
    "gk": "tidak",
    "ga" : "tidak",
    "gak": "tidak",
    "hrs": "harus",
    "skrg": "sekarang",
    "blm": "belum",
    "n": "dan",
    "d": "di",
    "dr": "dari",
    "dri": "dari",
    "smua": "semua",
    "sm": "sama",
    "gini": "begini",
    "bwt": "buat",
    "gini": "begini",
    "kmrn": "kemarin",
    "kt": "kita",
    "dgn": "dengan",
    "utk": "untuk",
    "pke": "pakai",
    "pake": "pakai",
    "sya": "saya",
    "kyk": "seperti",
    "gmn": "bagaimana",
    "kyk": "seperti",
    "kayak": "seperti",
    "jdi": "jadi",
    "td": "tadi",
    "pnjg": "panjang",
    "bngt": "sekali",
    "ny": "nya",
    "tau": "tahu",
    "nnya": "nanya",
    "kau": "kamu",
    "r": "are",
    "kmu": "kamu",
    "gtu": "begitu",
    "gtw": "tidak tahu",
    "bgtu": "begitu",
    "bngt": "sekali",
    "p": "pergi",
    "ama": "sama",
    "brg": "barang",
    "krm": "kirim",
    "drpd": "daripada",
    "lgi": "lagi",
    "bkn": "bukan",
    "drpd": "daripada",
    "dpt": "dapat",
    "lgsg": "langsung",
    "msh": "masih",
    "dlm": "dalam",
    "yg": "yang",
    "tuh": "itu",
    "skt": "sakit",
    "dl": "dulu",
    "sbg": "sebagai",
    "gt": "begitu",
    "mksd": "maksud",
    "g": "tidak",
    "trus": "terus",
    "dlm": "dalam",
    "gmn": "bagaimana",
    "lg": "lagi",
    "dgr": "dengar",
    "sblm": "sebelum",
    "sy": "saya",
    "dkt": "dekat",
    "bgmn": "bagaimana",
    "ad": "ada",
    "knp": "kenapa",
    "k": "ke",
    "bnyk": "banyak",
    "dtg": "datang",
    "jg": "juga",
    "bwt": "buat",
    "jd": "jadi",
    "sbgi": "sebagai",
    "smpe": "sampai",
    "bnyk": "banyak",
    "hr": "hari",
    "msh": "masih",
    "tp": "tapi",
    "kn": "kan",
    "tpi": "tapi",
    "udh": "udah",
    "yng": "yang",
    "kpn": "kapan",
    "dpt": "dapat",
    "bgs": "bagus",
    "kalo": "kalau",
    "drpd": "daripada",
    "kmu": "kamu",
    "km": "kamu",
    "lmyn": "lumayan",
    "mnrt": "menurut",
    "bgtu": "begitu",
    "lg": "lagi",
    "msh": "masih",
    "krna": "karena",
    "nyg": "yang",
    "nyg": "yang",
    "tlg": "tolong",
    "lgsng": "langsung",
    "bkn": "bukan",
    "yah": "ya",
    "skli": "sekali",
    "bknnya": "bukannya",
    "krn": "karena",
    "brp": "berapa",
    "mungkin": "mungkin",
    "mnurut": "menurut",
    "jd": "jadi",
    "dpn": "depan",
    "yaa": "ya",
    "smw": "semua",
    "pdhl": "padahal",
    "bner": "benar",
    "utk": "untuk",
    "tp": "tapi",
    "dgn": "dengan",
    "dpt": "dapat",
    "sesuatu": "sesuatu",
    "bkn": "bukan",
    "lbh": "lebih",
    "blg": "bilang",
    "nnti": "nanti",
    "pnya": "punya",
    "mngkn": "mungkin",
    "mslh": "masalah",
    "dl": "dulu",
    "jdi": "jadi",
    "mngkn": "mungkin",
    "bbrp": "beberapa",
    "sblm": "sebelum",
    "apalagi": "apalagi",
    "mgkn": "mungkin",
    "hrs": "harus",
    "tnggl": "tinggal",
    "men": "memang",
    "mlh": "malah",
    "dri": "dari",
    "ntar": "nanti",
    "jd": "jadi",
    "knapa": "kenapa",
    "lgi": "lagi",
    "apa": "apa",
    "tuh": "itu",
    "ngmng": "ngomong",
    "nnti": "nanti",
    "jelas": "jelas",
    "jd": "jadi",
    "hr": "hari",
    "mgkin": "mungkin",
    "g": "tidak",
    "n": "dan",
    "ntn": "nonton",
    "lgsg": "langsung",
    "lw": "luar",
    "hri": "hari",
    "slalu": "selalu",
    "kmrn": "kemarin",
    "jd": "jadi",
    "emng": "memang",
    "bs": "bisa",
    "bnr": "benar",
    "bs": "bisa",
    "bs": "bisa",
    "gmna": "bagaimana",
    "kq": "kok",
    "bgt": "sekali",
    "bgs": "bagus",
    "gt": "begitu",
    "bngt": "sekali",
    "gmn": "bagaimana",
    "nya": "",
    "h": "hari",
    "hr": "hari",
    "hr": "hari",
    "jd": "jadi",
    "kl": "kalau",
    "ma": "maka",
    "bat": "sekali",
    "â": "",
    "ae": "aja",
    "mnrt": "menurut",
    "ntr": "nanti",
    "pnjg": "panjang",
    "ptg": "petang",
    "smpe": "sampai",
    "smuanya": "semuanya",
    "tdk": "tidak",
    "terusin": "teruskan",
    "tmn": "teman",
    "tuh": "itu",
    "yt": "youtube",
    "yg": "yang",
    "zn": "zaman",
    "zmn": "zaman",
    "cpt": "cepat",
    "cepet": "cepat",
    "banget": "sekali",
    "sdh": "sudah",
    "bgsd": "bangsat",
    "bikin": "buat",
}

def replace_words(tokens):
    # Lakukan penggantian kata-kata tidak baku
    tokens_replaced = [replacement_dict.get(word, word) for word in tokens]
    return tokens_replaced

# Daftar stopwords dalam bahasa Indonesia
stopwords_id = {
    "yang", "yg","amp", "di", "dan", "dari", "dalam", "pada", "oleh", "atau", "untuk", "ini",
    "itu", "akan", "ke", "saya", "karena", "jika", "kita", "tidak", "hanya", "dengan",
    "adalah", "sebagai", "bagi", "ataupun", "ini", "kamu", "mereka", "telah", "dapat",
    "kami", "seorang", "tentang", "dapat", "lagi", "belum", "sekarang", "mungkin",
    "orang", "ia", "harus", "begitu", "atau", "dapat", "akan", "sampai", "bisa",
    "lain", "kalau", "juga", "dalam", "masih", "melakukan", "sudah", "agar", "lalu",
    "setelah", "semua", "ia", "dia", "anda", "saat", "sementara", "kita", "selama", "tapi",
    "antara", "sedangkan", "sebelum", "hanya", "terhadap", "demi", "seperti", "tak",
    "sebab", "ada", "maka", "perlu", "atau", "sebab", "setiap", "tentu", "maka",
    "sebab", "setiap", "tentu", "sekarang", "kembali", "saja", "aja", "lainnya", "harus",
    "jangan", "baik", "seolah", "paling", "agar", "sehingga", "begitu", "dapat",
    "terus", "memang", "demikian", "sempat", "berikut", "banyak", "pula", "sering",
    "memper", "apalagi", "begitupun", "langsung", "sejak", "sebetulnya", "berkali",
    "semakin", "bermaksud", "tampak", "setiap", "kali", "sesuatu", "karena", "sesudah",
    "selain", "sambil", "apalagi", "tetapi", "sungguh", "sesudah", "mula", "beraneka",
    "terus", "ingin", "lagi", "betapa", "kala", "meskipun", "seperti", "bagaimanapun",
    "sehingga", "sedangkan", "satu", "sama", "ada", "dua", "tiga", "empat", "lima",
    "enam", "tujuh", "delapan", "sembilan", "sepuluh", "belas", "puluh", "seratus",
    "ribu", "juta", "pada", "ini", "itu", "itu", "dari", "yang", "dari", "ini", "itu",
    "kami", "kamu", "mereka", "dia", "itu", "ini", "dan", "atau", "juga", "serta",
    "sambil", "sedangkan", "sementara", "tetapi", "lalu", "kemudian", "setelah",
    "sebelum", "begitu", "saja", "hanya", "bahkan", "pun", "jika", "kalau", "sekiranya",
    "bilamana", "apabila", "seumpamanya", "meskipun", "walaupun", "sebab", "karena",
    "oleh", "dengan", "guna", "untuk", "bagi", "pada", "di", "ke", "dari", "atas",
    "bawah", "menuju", "berupa", "seperti", "dari", "dalam", "luar", "kecuali",
    "selain", "serta", "dan", "atau", "ataupun", "dan", "lain", "lainnya", "lainnya",
    "dst", "dlsb", "dll", "dan", "seterusnya", "dsb", "dsbnya", "dstnya", "lah", "sih"
}

# Fungsi penghapusan stopwords
def remove_stopwords(tokens):
    # Menghapus stopwords dari token
    tokens_without_stopwords = [word for word in tokens if word not in stopwords_id]
    return tokens_without_stopwords

factory = StemmerFactory()
stemmer = factory.create_stemmer()

def stem_tokens(tokens):
    stemmed_tokens = [stemmer.stem(token) for token in tokens]
    return stemmed_tokens

# Buat fungsi untuk membaca data dari MySQL
def read_data_from_mysql(host, user, password, database):
    try:
        # Membuat koneksi ke database MySQL
        connection = mysql.connector.connect(
            host="localhost",
            user="root",
            password="",
            database="ta"
        )
        
        # Membuat cursor
        cursor = connection.cursor()

        # Mengeksekusi query
        cursor.execute(query)

        # Mendapatkan hasil query
        result = cursor.fetchall()

        # Mengonversi hasil query ke DataFrame pandas
        columns = [i[0] for i in cursor.description]
        data = pd.DataFrame(result, columns=columns)

        # Menutup kursor dan koneksi
        cursor.close()
        connection.close()


        return data
    except mysql.connector.Error as err:
        print(f"Error: {err}")
        return None

# Masukkan informasi koneksi ke database MySQL di sini
host = "localhost"  
user = "root"   
password = ""  
database = "ta"  
query = "SELECT * FROM dataraw"  

# Baca data dari database MySQL
data = read_data_from_mysql(host, user, password, database)

if data is not None:
    # Membersihkan data dari nilai null
    cleaned_data = data.copy()  # Buat salinan data agar tidak mengubah data asli
    cleaned_data['cleaned_text'] = cleaned_data['text'].apply(clean_text)
    cleaned_data['tokens'] = cleaned_data['cleaned_text'].apply(tokenize_text)

    # Menerapkan fungsi penggantian kata-kata tidak baku
    cleaned_data['tokens_replaced'] = cleaned_data['tokens'].apply(replace_words)

    cleaned_data['tokens_without_stopwords'] = cleaned_data['tokens_replaced'].apply(remove_stopwords)

    cleaned_data['stemmed_tokens'] = cleaned_data['tokens_without_stopwords'].apply(stem_tokens)

    cleaned_data['text'] = cleaned_data['stemmed_tokens'].apply(lambda tokens: ' '.join(tokens))

    # Mengonversi list tokens dan tokens_replaced menjadi string
    cleaned_data['tokens'] = cleaned_data['tokens'].apply(json.dumps)
    cleaned_data['tokens_replaced'] = cleaned_data['tokens_replaced'].apply(json.dumps)
    cleaned_data['tokens_without_stopwords'] = cleaned_data['tokens_without_stopwords'].apply(json.dumps)
    cleaned_data['stemmed_tokens'] = cleaned_data['stemmed_tokens'].apply(json.dumps)

    

    try:
        # Membuat koneksi ke database MySQL
        connection = mysql.connector.connect(
            host="localhost",
            user="root",
            password="",
            database="ta"
        )
        
        # Membuat koneksi ke database MySQL
        engine = create_engine('mysql+mysqlconnector://root:@localhost/ta')
        
        # Simpan data ke dalam tabel 'preprocessing' di database MySQL
        cleaned_data[['id', 'username', 'text', 'label', 'cleaned_text', 'tokens', 'tokens_replaced', 'tokens_without_stopwords', 'stemmed_tokens']].to_sql(name='preprocessing', con=engine, if_exists='append', index=False)

        # Commit perubahan
        connection.commit()

        # Menutup koneksi
        connection.close()
    


        print("Data telah disimpan ke dalam tabel di database MySQL.")
    except mysql.connector.Error as err:
        print(f"Error: {err}")
else:
    print("Tidak ada data untuk dibaca.")
    


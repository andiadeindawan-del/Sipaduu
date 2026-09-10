
path = "app/Models/User.php"
with open(path, "r", encoding="utf-8") as f:
    content = f.read()

new_fields = """
        // Digitalisasi & Pemasaran Baru
        'judul_usaha_online', 'shopee', 'tokopedia', 'lazada', 'blibli', 'marketplace_lainnya', 'wilayah_pemasaran',
"""

content = content.replace("'email_usaha', 'website_usaha',", "'email_usaha', 'website_usaha'," + new_fields)

with open(path, "w", encoding="utf-8") as f:
    f.write(content)
print("Added to fillable")

import re
path = "app/Http/Controllers/ProfileController.php"
with open(path, "r", encoding="utf-8") as f:
    content = f.read()

# I want to remove:
#             $validated['file_produk'] = $request->file('file_produk')->store('produk_files', 'public');
#         }
bad_str = "            $validated['file_produk'] = $request->file('file_produk')->store('produk_files', 'public');\n        }"

content = content.replace(bad_str, "")

with open(path, "w", encoding="utf-8") as f:
    f.write(content)

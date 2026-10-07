import re

# 1. Fix ProfileController.php
ctrl_path = r'c:\laragon\www\SIPADUU\app\Http\Controllers\ProfileController.php'
with open(ctrl_path, 'r', encoding='utf-8') as f:
    ctrl = f.read()

ctrl = ctrl.replace("'avatar' => [$user->foto ? 'nullable' : 'required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],", "'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],")
ctrl = ctrl.replace("'file_produk' => [$user->file_produk ? 'nullable' : 'required', 'array'],", "'file_produk' => ['nullable', 'array'],")

with open(ctrl_path, 'w', encoding='utf-8') as f:
    f.write(ctrl)

# 2. Fix UserController.php
user_ctrl_path = r'c:\laragon\www\SIPADUU\app\Http\Controllers\UserController.php'
with open(user_ctrl_path, 'r', encoding='utf-8') as f:
    user_ctrl = f.read()

user_ctrl = user_ctrl.replace("'avatar' => [$user->foto ? 'nullable' : 'required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],", "'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],")
user_ctrl = user_ctrl.replace("'file_produk' => [$user->file_produk ? 'nullable' : 'required', 'array'],", "'file_produk' => ['nullable', 'array'],")

with open(user_ctrl_path, 'w', encoding='utf-8') as f:
    f.write(user_ctrl)

# 3. Fix blade files
views = [
    r'c:\laragon\www\SIPADUU\resources\views\peserta\profile\index.blade.php',
    r'c:\laragon\www\SIPADUU\resources\views\admin\users\edit.blade.php'
]

for view in views:
    with open(view, 'r', encoding='utf-8') as f:
        html = f.read()

    # Remove required from avatar
    html = html.replace("accept=\"image/*\" {{ $user->foto ? '' : 'required' }}>", "accept=\"image/*\">")
    
    # Remove required from file_produk
    html = html.replace("accept=\".jpg,.jpeg,.png,.pdf,.doc,.docx\" {{ $user->file_produk ? '' : 'required' }}>", "accept=\".jpg,.jpeg,.png,.pdf,.doc,.docx\">")

    # Are there any other required fields that might block it?
    # KTP file?
    html = html.replace("accept=\".jpg,.jpeg,.png,.pdf\" {{ $user->ktp_file ? '' : 'required' }}>", "accept=\".jpg,.jpeg,.png,.pdf\">")

    with open(view, 'w', encoding='utf-8') as f:
        f.write(html)

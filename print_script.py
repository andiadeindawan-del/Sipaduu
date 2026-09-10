
import re

with open("admin_edit_dump.txt", "r", encoding="utf-8") as f:
    html = f.read()

scripts = re.findall(r"<script>(.*?)</script>", html, re.DOTALL)
print(scripts[1])

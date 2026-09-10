
import re

with open("admin_edit_dump.txt", "r", encoding="utf-8") as f:
    html = f.read()

scripts = re.findall(r"<script>(.*?)</script>", html, re.DOTALL)
for i, s in enumerate(scripts):
    print(f"Script {i} length: {len(s)}")
    # quick check for mismatched braces
    open_b = s.count("{")
    close_b = s.count("}")
    open_p = s.count("(")
    close_p = s.count(")")
    print(f"Script {i} braces: {{ {open_b}, }} {close_b}")
    print(f"Script {i} parens: ( {open_p}, ) {close_p}")


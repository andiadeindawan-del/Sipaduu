
import re

with open("resources/views/admin/users/edit.blade.php", "r", encoding="utf-8") as f:
    html = f.read()

def check_tags(tag):
    open_tags = len(re.findall(r"<%s\b[^>]*>" % tag, html))
    close_tags = len(re.findall(r"</%s>" % tag, html))
    print(f"Tag <{tag}>: Open={open_tags}, Close={close_tags}, Diff={open_tags - close_tags}")

check_tags("div")

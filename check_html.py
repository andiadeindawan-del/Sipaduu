
from bs4 import BeautifulSoup

with open("resources/views/admin/users/edit.blade.php", "r", encoding="utf-8") as f:
    content = f.read()

soup = BeautifulSoup(content, "html.parser")
forms = soup.find_all("form")
for form in forms:
    print(f"Form action: {form.get('action')}")
    tabs = form.find_all("div", class_="tab-pane")
    print(f"Tabs inside form: {len(tabs)}")
    for tab in tabs:
        print(f" - Tab: {tab.get('id')}")

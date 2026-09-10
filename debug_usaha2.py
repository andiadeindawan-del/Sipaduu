
from html.parser import HTMLParser

class UsahaParser(HTMLParser):
    def __init__(self):
        super().__init__()
        self.stack = []
        
    def handle_starttag(self, tag, attrs):
        if tag == "div":
            attr_dict = dict(attrs)
            classes = attr_dict.get("class", "")
            id_val = attr_dict.get("id", "")
            self.stack.append((self.getpos()[0], f"class=\"{classes}\" id=\"{id_val}\""))
            
    def handle_endtag(self, tag):
        if tag == "div" and self.stack:
            self.stack.pop()

parser = UsahaParser()
with open("admin_edit_dump.txt", "r", encoding="utf-8") as f:
    lines = f.readlines()

usaha_content = "".join(lines[191:370]) # stop before digital tab
parser.feed(usaha_content)

print("Remaining open divs:")
for line, desc in parser.stack:
    print(f"Line {line + 191}: <div {desc}>")

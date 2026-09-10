
from html.parser import HTMLParser

class MyHTMLParser(HTMLParser):
    def __init__(self):
        super().__init__()
        self.depth = 0
        self.stack = []
        
    def handle_starttag(self, tag, attrs):
        if tag == "div":
            self.depth += 1
            attr_dict = dict(attrs)
            classes = attr_dict.get("class", "")
            id_val = attr_dict.get("id", "")
            self.stack.append((self.getpos()[0], f"class=\"{classes}\" id=\"{id_val}\""))
            
    def handle_endtag(self, tag):
        if tag == "div" and self.stack:
            self.stack.pop()
            self.depth -= 1

parser = MyHTMLParser()
with open("resources/views/admin/users/edit.blade.php", "r", encoding="utf-8") as f:
    content = f.read()
parser.feed(content)

for line, desc in parser.stack:
    print(f"Line {line}: <div {desc}>")

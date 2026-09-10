
from html.parser import HTMLParser

class UsahaParser(HTMLParser):
    def __init__(self):
        super().__init__()
        self.depth = 0
        self.tags = []
        
    def handle_starttag(self, tag, attrs):
        if tag == "div":
            self.depth += 1
            self.tags.append((self.getpos()[0], "div", "open", self.depth))
            
    def handle_endtag(self, tag):
        if tag == "div":
            self.tags.append((self.getpos()[0], "div", "close", self.depth))
            self.depth -= 1

parser = UsahaParser()
with open("admin_edit_dump.txt", "r", encoding="utf-8") as f:
    lines = f.readlines()

usaha_content = "".join(lines[191:375])
parser.feed(usaha_content)

print("Usaha Tab Final Depth:", parser.depth)

for t in parser.tags:
    if t[3] < 0:
        print("Negative depth at line", t[0] + 191)

print("Tags that don't match:")
# print the lines that open but don't close

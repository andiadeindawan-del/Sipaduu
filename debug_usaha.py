
from html.parser import HTMLParser
import re

class UsahaParser(HTMLParser):
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
            desc = f"class=\"{classes}\" id=\"{id_val}\""
            self.stack.append((self.getpos()[0], desc))
            
    def handle_endtag(self, tag):
        if tag == "div":
            self.depth -= 1
            if self.stack:
                self.stack.pop()

parser = UsahaParser()
with open("admin_edit_dump.txt", "r", encoding="utf-8") as f:
    lines = f.readlines()

usaha_content = "".join(lines[191:375])
parser.feed(usaha_content)

print("Remaining open divs:")
for line, desc in parser.stack:
    # the line number here is relative to usaha_content string (1-indexed based on snippet)
    real_line = line + 191
    print(f"Line {real_line}: <div {desc}>")

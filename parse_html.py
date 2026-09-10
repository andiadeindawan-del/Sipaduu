
from html.parser import HTMLParser

class MyHTMLParser(HTMLParser):
    def __init__(self):
        super().__init__()
        self.depth = 0
        self.div_depths = []
        
    def handle_starttag(self, tag, attrs):
        if tag == "div":
            self.depth += 1
            self.div_depths.append((self.getpos()[0], "open", self.depth))
            
    def handle_endtag(self, tag):
        if tag == "div":
            self.div_depths.append((self.getpos()[0], "close", self.depth))
            self.depth -= 1

parser = MyHTMLParser()
with open("admin_edit_dump.txt", "r", encoding="utf-8") as f:
    content = f.read()
parser.feed(content)

print("Final Depth:", parser.depth)

# Let's track depth by line
current_depth = 0
line_depths = {}
for line, action, d in parser.div_depths:
    if action == "open":
        current_depth = d
    else:
        current_depth = d - 1
    line_depths[line] = current_depth

for line in sorted(line_depths.keys()):
    if line_depths[line] < 0 or (line > 100 and line_depths[line] < 5): 
        # normally inside .tab-content it's around depth 5 or 6
        pass

# Print tab pane start depths to see which one doesn't close
tab_panes = [
    "id=\"pribadi\"",
    "id=\"usaha\"",
    "id=\"digital\"",
    "id=\"tambahan\"",
    "id=\"dokumen\""
]

lines = content.split("\n")
for i, l in enumerate(lines):
    for tab in tab_panes:
        if tab in l:
            # what is the depth at this line?
            d = line_depths.get(i+1, -1)
            # if not exact line, find closest
            if d == -1:
                for offset in range(1, 10):
                    if i+1-offset in line_depths:
                        d = line_depths[i+1-offset]
                        break
            print(f"Tab {tab} at line {i+1}, Depth={d}")
            
# Also check form end
for i, l in enumerate(lines):
    if "</form>" in l:
        d = line_depths.get(i+1, -1)
        if d == -1:
            for offset in range(1, 10):
                if i+1-offset in line_depths:
                    d = line_depths[i+1-offset]
                    break
        print(f"Form ends at line {i+1}, Depth={d}")

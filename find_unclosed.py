
import re

with open("admin_edit_dump.txt", "r", encoding="utf-8") as f:
    lines = f.readlines()

depth = 0
for i, line in enumerate(lines):
    # simple heuristic: count <div...> and </div>
    # this might fail if they are in comments, but let's try
    open_divs = len(re.findall(r"<div\b[^>]*>", line))
    close_divs = len(re.findall(r"</div>", line))
    
    depth += (open_divs - close_divs)
    
    if "<!-- TAB " in line or "class=\"tab-pane" in line or "card bg-light" in line:
        print(f"Line {i+1}: Depth={depth} | {line.strip()}")

print("Final Depth:", depth)

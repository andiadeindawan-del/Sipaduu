import re
content = open(r'C:\Users\ASUS\.gemini\antigravity\brain\513d028c-73f2-444c-9aac-c67756663c5f\.system_generated\steps\270\content.md', encoding='utf-8').read()
urls = set(re.findall(r'https?://[^\s\"\']+', content))
with open('urls.txt', 'w') as f:
    for u in sorted(urls):
        f.write(u + '\n')

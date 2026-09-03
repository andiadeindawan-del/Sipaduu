import urllib.request
import re

url = 'https://html.duckduckgo.com/html/?q=site:raw.githubusercontent.com+"kbli"+"json"'
req = urllib.request.Request(url, headers={'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)'})
html = urllib.request.urlopen(req).read().decode('utf-8')
links = set(re.findall(r'https?://raw\.githubusercontent\.com/[a-zA-Z0-9_.-]+/[a-zA-Z0-9_.-]+/[a-zA-Z0-9_./-]+', html))
for link in links:
    print(link)

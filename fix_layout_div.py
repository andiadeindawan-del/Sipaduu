
path = "resources/views/admin/users/edit.blade.php"
with open(path, "r", encoding="utf-8") as f:
    content = f.read()

bad = """                </form>
            </div>
            
            
        <div class="col-12 col-lg-10 mx-auto mt-4">"""

good = """                </form>
            </div>
        </div>
        
        <div class="col-12 col-lg-10 mx-auto mt-4">"""

content = content.replace(bad, good)

with open(path, "w", encoding="utf-8") as f:
    f.write(content)
print("Added missing layout div")

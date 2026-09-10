
path = "resources/views/admin/users/edit.blade.php"
with open(path, "r", encoding="utf-8") as f:
    content = f.read()

bad = """                                <div class="col-12 mt-4 mb-2">
                                  <div class="col-12 mt-4 mb-2">
                                      <h6 class="fw-bold border-bottom pb-2">Data Tenaga Kerja <span class="text-danger">*</span></h6>
                                  </div>"""

good = """                                  <div class="col-12 mt-4 mb-2">
                                      <h6 class="fw-bold border-bottom pb-2">Data Tenaga Kerja <span class="text-danger">*</span></h6>
                                  </div>"""

content = content.replace(bad, good)

with open(path, "w", encoding="utf-8") as f:
    f.write(content)
print("Removed extra div")


path = "resources/views/admin/users/edit.blade.php"
with open(path, "r", encoding="utf-8") as f:
    content = f.read()

bad = """    $(document).on('click', '.remove-mp', function() {
        $(this).closest('.mp-row').remove();
    });

</script>"""

good = """</script>"""

content = content.replace(bad, good)

with open(path, "w", encoding="utf-8") as f:
    f.write(content)
print("Removed duplicate JS that caused ReferenceError")


import re
# Fix ProfileController
path_prof = "app/Http/Controllers/ProfileController.php"
with open(path_prof, "r") as f: content = f.read()
# When saving name, sync nama
content = content.replace("        $user->fill($validated);\n\n        if ($user->isDirty('email'))", 
"        $user->fill($validated);\n        if (isset($validated['name'])) { $user->nama = $validated['name']; }\n\n        if ($user->isDirty('email'))")

# Wait, ProfileController uses $user->update($validated) instead of fill()? Let's check:

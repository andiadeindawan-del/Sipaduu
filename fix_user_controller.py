import re

with open("app/Http/Controllers/UserController.php", "r", encoding="utf-8") as f:
    content = f.read()

# Replace $user->update($data) with mapping logic and then update
logic = """
        // Mapping marketplace lainnya
        $marketplaces = [];
        if (isset($data['marketplace_lainnya_nama']) && is_array($data['marketplace_lainnya_nama'])) {
            foreach ($data['marketplace_lainnya_nama'] as $index => $nama) {
                $link = $data['marketplace_lainnya_link'][$index] ?? null;
                if (!empty($nama) || !empty($link)) {
                    $marketplaces[] = [
                        'nama' => $nama,
                        'link' => $link
                    ];
                }
            }
        }
        $data['marketplace_lainnya'] = $marketplaces;
        unset($data['marketplace_lainnya_nama']);
        unset($data['marketplace_lainnya_link']);

        $user->update($data);"""

old = r"\$user->update\(\$data\);"
content = re.sub(old, logic, content, count=1)

with open("app/Http/Controllers/UserController.php", "w", encoding="utf-8") as f:
    f.write(content)

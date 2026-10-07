<?php
$content = file_get_contents("app/Http/Controllers/SertifikatController.php");
$search = "} catch (\Exception \$e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return redirect()->back()->withInput()->with('error', \$e->getMessage());
        }
    private function getUserPassingStatus";
$replace = "} catch (\Exception \$e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return redirect()->back()->withInput()->with('error', \$e->getMessage());
        }
    }
    private function getUserPassingStatus";
$content = str_replace($search, $replace, $content);
file_put_contents("app/Http/Controllers/SertifikatController.php", $content);
echo "Done";

<?php
session_start();


$title = "Items";

// cheak if found session name
if (isset($_SESSION['Admin'])) {

    include "init.php";


    // Cheak if found the action in the title

    $action = isset($_GET['action']) ? $_GET['action'] : 'items';

    if ($action == 'items') {

        $stam = $con->prepare("SELECT item.*,admins.Username 
                                AS Admin_add 
                                FROM item
                        
                                INNER JOIN admins
                                ON admins.ID=item.member_id;");
        $stam->execute();
        $rows = $stam->fetchAll();

        ?>
        <h1 class="text-center">Manage items</h1>

        <div class="container">
            <div class="table">
                <table class="main-table text-center table table-bordered table-items">
                    <thead>
                        <tr>
                            <td>ID</td>
                            <td>Nameitem</td>
                            <td>Caption</td>
                            <td>Price</td>
                            <td>imgitem</td>
                            <td>UserAdd</td>
                            <td>Date</td>
                            <td>Control</td>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($rows as $row) {


                            echo "<tr class='tr-item'>";

                            echo "<td> $row[ID] </td>";
                            echo "<td> $row[name_item] </td>";
                            echo "<td> $row[caption] </td>";
                            echo "<td> $row[price] </td>";
                            echo "<td> <img class='img_item' src='uploded/img-uploded/$row[single_image]'></img>   </td>";

                            echo "<td> $row[Admin_add] </td>";
                            echo "<td> $row[date] </td>";

                            echo "<td>";
                            echo "<a href='items.php?action=Edit&ID=$row[ID]' class='btn btn-success'><i class='fa-regular fa-pen-to-square'></i>Edit</a>";
                            echo "<a href='items.php?action=delet&ID=$row[ID]' class='btn btn-danger confirm'><i class='fa-solid fa-xmark'></i>Remove</a>";
                            echo "</td>";

                            echo "</tr>";

                        }
                        ?>
                    </tbody>
                </table>
                <a href="items.php?action=add" class="btn btn-primary"><i class="fa fa-plus"></i>New item</a>

            </div>
        </div>

    <?php } elseif ($action == 'add') {


        ?>

        <h1 class="text-center">Add item</h1>
        <div class="container">
            <form action="?action=insert" class="form-horizontal" method="POST" enctype="multipart/form-data">

                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Nameitem</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="name" class="form-control" placeholder="Type Name The item" required />
                    </div>
                </div>
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Caption</label>
                    <div class="col-sm-10 col-md-6">
                        <textarea name="caption" class="form-control" placeholder="Type The Script For item"
                            required></textarea>
                    </div>
                </div>
                <div class=" form-group form-group-lg">
                    <label class="col-sm-2 control-label">Price</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="price" class="form-control" placeholder="Type Price For item"
                            required="required">
                    </div>
                </div>
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">imgitem</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="file" name="img" class="form-control" required="required">
                    </div>
                </div>
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Choose The Gallery For Item</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="file" name="Gallery[]" class="form-control" multiple="multiple" required="required">
                    </div>
                </div>
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">UserAdd</label>
                    <div class="col-sm-10 col-md-6">
                        <details class="custom-select">
                            <summary class="radios">
                                <input type="radio" name="user" id="default" title="....." value="" checked>
                                <?php
                                $stam = $con->prepare("SELECT * FROM admins");
                                $stam->execute();
                                $rows = $stam->fetchAll();

                                foreach ($rows as $row) {
                                    echo "<input type='radio' name='user' id='$row[ID]' title='$row[Username]' value='$row[ID]' required>";
                                }
                                ?>
                            </summary>
                            <ul class="list">

                                <?php foreach ($rows as $row) {
                                    echo "<li>";
                                    echo "<label for='$row[ID]'>";
                                    echo $row['Username'];
                                    echo "</label>";
                                    echo "</li>";
                                } ?>
                            </ul>
                        </details>
                    </div>
                </div>
                <div class="form-group form-group-lg">
                    <div class="col-sm-offset-2 col-sm-10 col-md-6">
                        <input type="submit" name="submit" value="Sign" class="btn btn-primary btn-lg">
                    </div>
                </div>

            </form>
        </div>



    <?php } elseif ($action == "insert") {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {

            // Gathering form data
            $imgGroupN = $_FILES['Gallery']['name']; // For multiple images
            $imgGroupT = $_FILES['Gallery']['type'];
            $imgGroupTN = $_FILES['Gallery']['tmp_name'];
            $imgGroupS = $_FILES['Gallery']['size'];

            $imgName = $_FILES['img']['name']; // For single image
            $imgType = $_FILES['img']['type'];
            $imgTmp = $_FILES['img']['tmp_name'];
            $imgSize = $_FILES['img']['size'];

            $allowed_types = array("jpg", "gif", "jpeg", "png");
            
            $formErrors = [];

            // Data for the product
            $name = $_POST['name'];
            $caption = $_POST['caption'];
            $price = $_POST['price'];
            $userAdd = $_POST['user'];

            // Checking single image (if provided)
            if (empty($imgName)) {
                $formErrors[] = "No file uploaded for the single image.";
            }

            $type_img = strtolower(pathinfo($imgName, PATHINFO_EXTENSION));

            

            if (!in_array($type_img, $allowed_types)) {
                $formErrors[] = "Invalid file type for single image. Allowed types are: JPG, GIF, JPEG, PNG.";
            }

            // Multiple image checks
            $count_img = count($imgGroupN);
            $image_names = [];
            for ($i = 0; $i < $count_img; $i++) {
                $type_img_group = strtolower(pathinfo($imgGroupN[$i], PATHINFO_EXTENSION));

                if (empty($imgGroupN[$i])) {
                    $formErrors[] = "No file uploaded for image " . ($i + 1);
                }

                

                if (!in_array($type_img_group, $allowed_types)) {
                    $formErrors[] = "Invalid file type for image " . ($i + 1) . ". Allowed types are: JPG, GIF, JPEG, PNG.";
                }

                // Save image names for storing in DB
                if (!empty($imgGroupN[$i])) {
                    $rand_name_group = rand(0, 1000000000) . '_' . $imgGroupN[$i];
                    move_uploaded_file($imgGroupTN[$i], "uploded/group-img/" . $rand_name_group);
                    $image_names[] = $rand_name_group;
                }
            }

            // Additional form validation
            if (empty($name)) {
                $formErrors[] = "Item name can't be empty.";
            }

            if (empty($caption)) {
                $formErrors[] = "Caption can't be empty.";
            }

            if (empty($price)) {
                $formErrors[] = "Price can't be empty.";
            }

            if (empty($userAdd)) {
                $formErrors[] = "User can't be empty.";
            }

            // If no errors, proceed to insert data
            if (empty($formErrors)) {

                // Check if the name already exists in the database
                $cheak = cheakName("name_item", "item", $name);

                if ($cheak == 1) {
                    $mes = "<div class='alert alert-danger'>Sorry, this name already exists.</div>";
                    redrect($mes, "Add item", 2, "items.php?action=add");
                } else {
                    // Convert image names array into a string separated by commas
                    $images_str = implode(",", $image_names);

                    // Handle the single image
                    $rand_name_single = rand(0, 1000000000) . '_' . $imgName;
                    move_uploaded_file($imgTmp, "uploded/img-uploded/" . $rand_name_single);

                    // Insert item into the database with both single image and multiple images
                    $stam = $con->prepare("INSERT INTO item (name_item, caption, price, member_id, single_image, images) 
                                       VALUES (:n_item, :cap, :p, :mem_id, :single_image, :images)");

                    // Execute the query
                    $stam->execute([
                        ":n_item" => $name,
                        ":cap" => $caption,
                        ":p" => $price,
                        ":mem_id" => $userAdd,
                        ":single_image" => $rand_name_single,
                        // Store the single image
                        ":images" => $images_str // Store the string of multiple image names
                    ]);

                    $count = $stam->rowCount();

                    if ($count > 0) {
                        $mes = "<div class='alert alert-success'>The item and images have been added successfully.</div>";
                        redrect($mes, "Items", 2, "items.php");
                    }
                }
            } else {
                // Display errors
                foreach ($formErrors as $error) {
                    echo "<div class='container'>";
                    echo "<div class='alert alert-danger'>" . $error . "</div>";
                    echo "</div>";
                }
                header("refresh:2; url=items.php?action=add");
            }
        } else {
            $mes = "<div class='alert alert-danger'> You Cant'n Acsses In this page</div>";
            redrect($mes, "Dashbord");
        }
    } elseif ($action == 'Edit') {

        // if found id and id is number get this
        $IDuser = isset($_GET['ID']) && is_numeric($_GET['ID']) ? intval($_GET['ID']) : 0;

        // Cheak If The User Found In The Database
        $stam = $con->prepare("SELECT * FROM item WHERE ID=? LIMIT 1");
        $stam->execute(array($IDuser));
        $rowitem = $stam->fetch();
        $count = $stam->rowCount();

        if ($count > 0) {
            ?>

            <h1 class="text-center">Edit item</h1>

            <div class="container">
                <form action="?action=update" class="form-horizontal" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo $IDuser ?>">

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Nameitem</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="name" class="form-control" value="<?php echo $rowitem["name_item"] ?>"
                                required />
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Caption</label>
                        <div class="col-sm-10 col-md-6">
                            <textarea name="caption" class="form-control" required><?php echo $rowitem["caption"] ?></textarea>
                        </div>
                    </div>

                    <div class=" form-group form-group-lg">
                        <label class="col-sm-2 control-label">Price</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="price" class="form-control" value="<?php echo $rowitem["price"] ?>" required>
                        </div>
                    </div>

                    <!-- صور متعددة -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Images</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="file" name="images[]" class="form-control" multiple>
                            <input type="hidden" name="old_images" value="<?php echo $rowitem["images"] ?>">
                        </div>
                    </div>

                    <!-- صورة واحدة -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Single Image</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="file" name="img" class="form-control">
                            <input type="hidden" name="old_img" value="<?php echo $rowitem["single_image"] ?>">
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">UserUpdate</label>
                        <div class="col-sm-10 col-md-6">
                            <details class="custom-select">
                                <summary class="radios">
                                    <input type="radio" name="user" id="default" title="....." value="" checked>
                                    <?php
                                    $stam = $con->prepare("SELECT * FROM admins");
                                    $stam->execute();
                                    $rows = $stam->fetchAll();

                                    foreach ($rows as $row) {
                                        echo "<input type='radio' name='user' id='$row[ID]' title='$row[Username]' value='$row[ID]' required >";
                                    }
                                    ?>
                                </summary>
                                <ul class="list">

                                    <?php foreach ($rows as $row) {
                                        echo "<li>";
                                        echo "<label for='$row[ID]'>";
                                        echo $row['Username'];
                                        echo "</label>";
                                        echo "</li>";
                                    } ?>
                                </ul>
                            </details>
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <div class="col-sm-offset-2 col-sm-10 col-md-6">
                            <input type="submit" name="submit" value="Update" class="btn btn-primary btn-lg">
                        </div>
                    </div>
                </form>
            </div>

            <?php
        } else {
            $mes = "<div class='alert alert-warning'> Can't Found This ID</div>";
            redrect($mes, "Dashbord");
        }
    } elseif ($action == 'update') {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            // استلام بيانات النموذج
            $imgNames = $_FILES['images']['name']; // أسماء الصور المتعددة
            $imgTypes = $_FILES['images']['type']; // أنواع الصور المتعددة
            $imgTmp = $_FILES['images']['tmp_name']; // المسار المؤقت للصور المتعددة
            $imgSizes = $_FILES['images']['size']; // أحجام الصور المتعددة

            // الصورة الفردية
            $singleImgName = $_FILES['img']['name'];
            $singleImgTmp = $_FILES['img']['tmp_name'];
            $singleImgSize = $_FILES['img']['size'];

            $allowed_types = array("jpg", "gif", "jpeg", "png");
            $idname = $_POST['id'];
            $name = $_POST['name'];
            $caption = $_POST['caption'];
            $price = $_POST['price'];
            $userAdd = $_POST['user'];

            // استعلام عن العنصر الذي سيتم تحديثه
            $stam = $con->prepare("SELECT * FROM item WHERE ID = ?");
            $stam->execute(array($idname));
            $rowitem = $stam->fetch();

            if (!$rowitem) {
                // في حال لم يتم العثور على الـ item
                echo "<div class='alert alert-warning'>This item does not exist!</div>";
                exit;
            }

            $formErrors = array();

            // التعامل مع الصور المتعددة
            $uploadedImages = [];
            if (!empty($imgNames[0])) { // إذا كانت هناك صور مرفوعة
                foreach ($imgNames as $index => $imgName) {
                    $imgTmpName = $imgTmp[$index];
                    $imgSize = $imgSizes[$index];

                    $get_type_img = explode(".", $imgName);
                    $type_img = strtolower(end($get_type_img));

                    



                    if (!in_array($type_img, $allowed_types)) {
                        $formErrors[] = "This Type Is Not Allowed";
                    }

                    // إذا كانت الصورة صالحة
                    if (empty($formErrors)) {
                        $rand_name = rand(0, 1000000000) . '_' . $imgName;
                        move_uploaded_file($imgTmpName, "uploded/img-uploded/" . $rand_name);
                        $uploadedImages[] = $rand_name; // إضافة اسم الصورة إلى المصفوفة
                    }
                }
            }

            // إضافة الصور المرفوعة إلى قاعدة البيانات
            $new_images = implode(",", $uploadedImages); // دمج الصور في سلسلة مفصولة بفواصل

            // إضافة صور جديدة أو الاحتفاظ بالصور القديمة إذا لم ترفع صور جديدة
            $old_images = $_POST['old_images'];
            $final_images = !empty($new_images) ? $new_images : $old_images;

            // التعامل مع الصورة الفردية (single image)
            $final_single_image = $rowitem['single_image']; // افتراض أن الصورة القديمة موجودة
            if (!empty($singleImgName)) {
                $get_type_single = explode(".", $singleImgName);
                $type_single = strtolower(end($get_type_single));

                

                if (!in_array($type_single, $allowed_types)) {
                    $formErrors[] = "This Type Of Single Image Is Not Allowed";
                }

                if (empty($formErrors)) {
                    $rand_name_single = rand(0, 1000000000) . '_' . $singleImgName;
                    move_uploaded_file($singleImgTmp, "uploded/img-uploded/" . $rand_name_single);
                    $final_single_image = $rand_name_single; // تحديث الصورة الفردية
                }
            }

            // التحقق من وجود أخطاء في النموذج
            if (empty($formErrors)) {
                // تحديث البيانات في قاعدة البيانات
                $stam = $con->prepare("UPDATE item SET name_item = ?, caption = ?, price = ?, single_image = ?, images = ?, member_id = ? WHERE ID = ?");
                $stam->execute(array($name, $caption, $price, $final_single_image, $final_images, $userAdd, $idname));

                $count = $stam->rowCount();
                if ($count > 0) {
                    $mes = "<div class='alert alert-success'> The Data Updated</div>";
                    redrect($mes, "Update item", 2, "items.php?action=Edit&ID=$idname");
                } else {
                    $mes = "<div class='alert alert-warning'>No Changes</div>";
                    redrect($mes, "Update item", 2, "items.php?action=Edit&ID=$idname");
                }
            } else {
                // إذا كانت هناك أخطاء في النموذج
                foreach ($formErrors as $error) {
                    echo "<div class='container'>";
                    echo "<div class='alert alert-warning'>$error</div>";
                    echo "</div>";
                }

                // header("refresh:2;url=items.php?action=Edit&ID=$idname");
            }

        } else {
            $mes = "<div class='alert alert-danger'>You Can't Access This Page</div>";
            redrect($mes, "Dashbord");
        }



    } elseif ($action == 'delet') {
    $IDitem = isset($_GET['ID']) && is_numeric($_GET['ID']) ? intval($_GET['ID']) : 0;

    // استعلام لجلب البيانات الخاصة بالعنصر
    $stam = $con->prepare("SELECT * FROM item WHERE ID=? LIMIT 1");
    $stam->execute(array($IDitem));
    $row_del = $stam->fetch();
    $row = $stam->rowCount();

    if ($row > 0) {
        echo "<pre>🔍 **Debug Information:**\n";
        
        // 🔹 طباعة محتويات المجلد للتأكد من أن الصور موجودة فعلاً
        $imgFolder = $_SERVER['DOCUMENT_ROOT'] . "/new_work/admin/uploded/img-uploded/";
        $groupImgFolder = $_SERVER['DOCUMENT_ROOT'] . "/new_work/admin/uploded/group-img/";

        echo "📂 محتويات مجلد الصور الفردية:\n";
        print_r(scandir($imgFolder));
        echo "\n📂 محتويات مجلد الصور الجماعية:\n";
        print_r(scandir($groupImgFolder));
        echo "</pre>";

        // 🔹 حذف الصورة الفردية
        if (!empty($row_del['single_image'])) {
            $singleImagePath = realpath($imgFolder . trim($row_del['single_image']));
            
            if ($singleImagePath && file_exists($singleImagePath)) {
                if (unlink($singleImagePath)) {
                    echo "✅ صورة واحدة حُذفت بنجاح: " . $singleImagePath . "<br>";
                } else {
                    echo "❌ فشل في حذف الصورة: " . $singleImagePath . "<br>";
                }
            } else {
                echo "⚠️ لم يتم العثور على الصورة الفردية: " . $singleImagePath . "<br>";
            }
        }

        // 🔹 حذف الصور المتعددة (إذا كانت موجودة)
        if (!empty($row_del['images'])) {
            $imageArray = explode(",", $row_del['images']);
            foreach ($imageArray as $image) {
                $imagePath = realpath($groupImgFolder . trim($image));
                
                if ($imagePath && file_exists($imagePath)) {
                    if (unlink($imagePath)) {
                        echo "✅ تم حذف الصورة: " . $imagePath . "<br>";
                    } else {
                        echo "❌ فشل في حذف الصورة: " . $imagePath . "<br>";
                    }
                } else {
                    echo "⚠️ لم يتم العثور على الصورة: " . $imagePath . "<br>";
                }
            }
        }

        // 🔹 حذف العنصر من قاعدة البيانات
        $stam = $con->prepare("DELETE FROM item WHERE ID = :did");
        $stam->bindParam("did", $IDitem);
        $stam->execute();

        // 🔹 رسالة تأكيد
        $mes = "<div class='alert alert-danger'>The item is deleted successfully.</div>";
        // redrect($mes, "items", 2, "items.php");
    } else {
        // 🔹 إذا لم يتم العثور على العنصر
        $mes = "<div class='alert alert-warning'>Item not found!</div>";
        // redrect($mes, "items", 2, "items.php"); 
    }

    echo "<br>🛠 **SERVER DOCUMENT ROOT:** " . $_SERVER['DOCUMENT_ROOT'];


}


} else {
    header('Location:index.php');
    exit();
}

include $ft . "footer.php";
?>
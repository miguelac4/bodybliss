<?php
include_once("basic_functions.php");
$conn = e_RuntimeReport();

$profile_pic = $_SESSION["profile_pic"] ?? "nullprofile.jpg";
$current_country = $_SESSION["country"] ?? '';
$current_phone = $_SESSION["phone"] ?? '';
?>
<head>
    <style>
        body {
            margin: 0;
            padding: 0;
            position: relative;
            background-color: #f5f5f5;
            font-family: Arial, sans-serif;
            overflow-x: hidden;
        }

        /* Overlay with image and fade */
        body::after {
            content: "";
            position: fixed;
            top: 0;
            right: 0;
            width: 50vw;
            height: 100vh;
            background: linear-gradient(to left, rgba(255,255,255,0) 0%, rgba(245,245,245,1) 70%),
            url('imgs/background_profile.jpg') no-repeat center center;
            background-size: cover;
            z-index: -1;
        }
    </style>
</head>


<div class="container mt-5">
    <div class="row">
        <!-- LEFT SIDE: profile with tabs -->
        <div class="col-md-4">
            <div class="card text-white border-0 card-shadow-out">
                <div class="text-center mt-4">
                    <img src="uploads_profile/<?= $profile_pic ?>" class="rounded-circle" width="120" height="120" style="object-fit: cover;" alt="Profile Picture">
                </div>
                <div class="card-body text-center">
                    <h4 class="mb-1"><?= $_SESSION["name"] ?></h4>
                    <p class="mb-2 text-white-50"><?= $_SESSION["email"] ?></p>
                </div>
            <div class="tab-list text-center">
                <div id="tab-btn-profile" class="tab-btn" onclick="showTab('profile')">
                    <i class="bi bi-person-fill"></i> Profile
                </div>
                <div id="tab-btn-edit" class="tab-btn" onclick="showTab('edit')">
                    <i class="bi bi-pencil-square"></i> Edit profile
                </div>
            </div>


        </div>
        </div>

        <!-- RIGHT SIDE: dinamic conteud -->
        <div class="col-md-8">
            <!-- PROFILE TAB -->
            <div id="tab-profile" class="tab-section">
                <div class="card card-flat-shadow card-round">
                    <div class="card-header text-white">
                        <?php
                        $phrases = [
                            "Close your eyes, breathe in deeply. In this quiet space, you are exactly where you need to be.",
                            "Meditation is not about escaping life, but arriving fully into it (with grace, stillness, and presence).",
                            "Let your thoughts pass like clouds. No judgment, just awareness. You are here now.",
                            "The peace you seek is already within you. Sit, breathe, and return to yourself.",
                            "Silence speaks. Inhale clarity, exhale chaos."
                        ];
                        $random_phrase = $phrases[array_rand($phrases)];
                        ?>
                        <em><?= $random_phrase ?></em>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title mb-4">Bio Graph</h5>
                        <div class="row mb-2"><div class="col-sm-4"><strong>First Name:</strong></div><div class="col-sm-8"><?= explode(" ", $_SESSION["name"])[0] ?></div></div>
                        <div class="row mb-2"><div class="col-sm-4"><strong>Last Name:</strong></div><div class="col-sm-8"><?= explode(" ", $_SESSION["name"])[1] ?? '' ?></div></div>
                        <div class="row mb-2"><div class="col-sm-4"><strong>Country:</strong></div><div class="col-sm-8"><?= $_SESSION["country"] ?? ''?></div></div>
                        <div class="row mb-2"><div class="col-sm-4"><strong>Email:</strong></div><div class="col-sm-8"><?= $_SESSION["email"] ?></div></div>
                        <div class="row mb-2"><div class="col-sm-4"><strong>Phone:</strong></div><div class="col-sm-8"><?= $_SESSION["phone"] ?? ''?></div></div>
                    </div>
                </div>
            </div>

            <!-- EDIT PROFILE TAB -->
            <div id="tab-edit" class="tab-section" style="display:none;">
                <div class="card card-flat-shadow">
                    <div class="card-header text-white">Edit Profile</div>
                    <div class="card-body">

                        <!-- Update profile FORM -->
                        <form action="upload_profile.php" method="POST" enctype="multipart/form-data" class="mb-4">
                            <div class="mb-4 text-center">
                                <label class="form-label fw-bold d-block">Update Profile Picture</label>

                                <!-- Pre-view (shows when choose images) -->
                                <img id="previewImage" src="uploads_profile/<?= $profile_pic ?>" class="rounded-circle mb-3 shadow" width="120" height="120" style="object-fit: cover;" alt="Preview">

                                <!-- Upload Fild -->
                                <div class="custom-file-upload">
                                    <input type="file" name="profile_pic" id="profilePicInput" accept="image/*" class="form-control d-none">
                                    <label for="profilePicInput" class="btn btn-outline-primary px-4">
                                        <i class="bi bi-upload me-2"></i> Choose Image
                                    </label>
                                </div>
                            </div>


                            <div class="mb-4">
                                <label class="form-label fw-bold">Update Country</label>
                                <select name="country" class="form-select">
                                    <option value=""></option>
                                    <option value="Portugal" <?= $current_country == 'Portugal' ? 'selected' : '' ?>>Portugal</option>
                                    <option value="Brasil" <?= $current_country == 'Brasil' ? 'selected' : '' ?>>Brasil</option>
                                    <option value="Espanha" <?= $current_country == 'Espanha' ? 'selected' : '' ?>>Espanha</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Update Phone</label>
                                <div class="input-group">
                                    <select name="country_code" class="form-select" style="max-width: 120px;">
                                        <option value="+351" <?= strpos($current_phone, '+351') === 0 ? 'selected' : '' ?>>+351</option>
                                        <option value="+55" <?= strpos($current_phone, '+55') === 0 ? 'selected' : '' ?>>+55</option>
                                        <option value="+34" <?= strpos($current_phone, '+34') === 0 ? 'selected' : '' ?>>+34</option>
                                    </select>
                                    <input type="text" name="phone" class="form-control"
                                           value="<?= preg_replace('/^\+\d+/', '', $current_phone) ?>">
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="submit" name="update_profile" class="btn btn-success px-4">
                                    <i class="bi bi-save me-1"></i> Save Changes
                                </button>
                            </div>
                        </form>

                        <!-- Separed form to delete images -->
                        <form action="delete_profile_pic.php" method="POST" class="text-end">
                            <button type="submit" name="remove" class="btn btn-outline-danger px-4">
                                <i class="bi bi-trash3 me-1"></i> Delete Profile Image
                            </button>
                        </form>



                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function showTab(tabId) {
        const tabs = ['profile', 'activity', 'edit'];
        tabs.forEach(id => {
            const tab = document.getElementById('tab-' + id);
            const btn = document.getElementById('tab-btn-' + id);

            if (tab) {
                tab.style.display = (id === tabId) ? 'block' : 'none';
            }

            if (btn) {
                btn.classList.toggle('active-tab', id === tabId);
            }
        });
    }

</script>

<script>
    const fileInput = document.getElementById('profilePicInput');
    const previewImage = document.getElementById('previewImage');

    fileInput.addEventListener('change', function () {
        const file = this.files[0];
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function (e) {
                previewImage.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });
</script>

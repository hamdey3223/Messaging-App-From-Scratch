<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <form id="profile-form" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="profile-file">
                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}?t={{ time() }}"
                             alt="Upload" class="img-fluid profile-image-preview">
                        <label for="select_file"><i class="fal fa-camera-alt"></i></label>
                        <input id="select_file" type="file" name="avatar" hidden accept="image/*">
                    </div>
                    <p>Edit information</p>
                    <input type="text" placeholder="Name" value="{{ auth()->user()->name }}" name="name" required>
                    <input type="email" placeholder="Email" value="{{ auth()->user()->email }}" name="email" required>
                    <input type="text" placeholder="User Id" value="{{ auth()->user()->user_name }}" name="user_id" required>
                    <p>Change password</p>
                    <div class="row">
                        <div class="col-xl-6">
                            <input type="password" placeholder="Old Password" name="old_password">
                        </div>
                        <div class="col-xl-6">
                            <input type="password" placeholder="New Password" name="password">
                        </div>
                        <div class="col-xl-12">
                            <input type="password" placeholder="Confirm Password" name="password_confirmation">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary cancel" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary save" id="saveProfile">Save changes</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const fileInput = document.getElementById("select_file");
        const previewImage = document.querySelector(".profile-image-preview");

        fileInput.addEventListener("change", function (event) {
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    previewImage.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });

        const notyf = new Notyf();

        document.getElementById("saveProfile").addEventListener("click", function () {
            let form = document.getElementById("profile-form");
            let formData = new FormData(form);

            fetch("{{ route('profile.update') }}", {
                method: "POST",
                body: formData,
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                },
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        notyf.success(data.message); // Show notification only in JavaScript
                        setTimeout(() => location.reload(), 1000); // Reload after 1 second
                    } else {
                        notyf.error(data.message);
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    notyf.error('An error occurred while updating the profile.');
                });
        });
    });

</script>




<?php
require '../_admin-required.php';
require '../parts/db_connect.php';
$pageName = 'add';
$title = 'Add';
?>

<?php include '../parts/html-head.php' ?>

<style>
    form .mb-3 .form-text {
        color: red;
    }
    span {
        font-size: 14px;
        color: #dc3545;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <!-- navbar -->
        <nav class="navbar navbar-expand-lg col-12" style="background-color: #003e52">
            <div class="container-fluid">
                <?php include '../parts/navbar.php' ?>
            </div>
        </nav>
        <!-- navbar -->

        <!-- sidebar -->
        <?php include '../parts/sidebar.php' ?>
        <!-- sidebar -->
        <div class="col-12 col-md-8 col-lg-10" style="background-color: #003e52">
            <div class="col-6">
                <!-- add breadcrumb start -->
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item "><a href="_index.php" class="text-decoration-none"><i class="bi bi-house-door"></i></a></li>
                        <li class="breadcrumb-item"><a href="date_friendships_msg_list.php" class="text-decoration-none">Friendship Message</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add</li>
                    </ol>
                </nav>
                <!-- add breadcrumb end -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Add Data</h5>
                        <form name="form1" method="post" novalidate onsubmit="sendForm(event)">
                            <!-- <div class="mb-3">
                                <label for="message_id" class="form-label">message_id</label>
                                <input type="text" class="form-control" id="message_id" name="message_id">
                                <div class="form-text"></div>
                            </div> -->
                            <div class="mb-3">
                                <label for="friendship_id" class="form-label">friendship_id</label>
                                <input type="text" class="form-control" id="friendship_id" name="friendship_id" minlength="1" maxlength="11" placeholder="Please enter the friendship_id. (Only number) " required>
                                <!-- <div class="invalid-feedback">Please provide a valid friendship_id.</div> -->
                                <span id="friendshipID"></span>
                                <div class="form-text"></div>
                            </div>
                            <div class="mb-3">
                                <label for="sender_id" class="form-label">sender_id</label>
                                <input type="text" class="form-control" id="sender_id" name="sender_id" minlength="1" maxlength="11" placeholder="Please enter the user_id. (Only number) " required>
                                <!-- <div class="invalid-feedback">Please provide a valid sender_id.</div> -->
                                <span id="senderID"></span>
                                <div class="form-text"></div>
                            <div class="mb-3">
                                <label for="receiver_id" class="form-label">receiver_id</label>
                                <input type="text" class="form-control" id="receiver_id" name="receiver_id" minlength="1" maxlength="11" placeholder="Please enter the user_id. (Only number) " required>
                                <!-- <div class="invalid-feedback">Please provide a valid receiver_id.</div> -->
                                <span id="receiverID"></span>
                                <div class="form-text"></div>
                            </div>
                            <div class="mb-3">
                                <label for="content" class="form-label">content</label>
                                <input type="text" class="form-control" id="content" name="content" minlength="1" maxlength="256" placeholder="Please enter the content." required>
                                <!-- <div class="invalid-feedback">Please provide a valid content.</div> -->
                                <span id="msgContent"></span>
                                <div class="form-text"></div>
                            </div>
                            <!-- <div class="mb-3">
                                <label for="sended_at" class="form-label">sended_at</label>
                                <textarea class="form-control" name="sended_at" id="sended_at" cols="30" rows="3"></textarea>
                            </div> -->
                            <button type="submit" class="btn btn-primary">Add</button>

                            <!-- Import CSV button -->
                            <div class="mb-3 mt-3">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadCsvModal"></i> Import from CSV</button>
                            </div>
                            <!-- Import CSV button -->
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Addition Result</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-success" role="alert">
                    Successfully added
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Continue</button>
                <a type="button" class="btn btn-primary" href="date_friendships_msg_list.php">Return</a>
            </div>
        </div>
    </div>
</div>
<!-- Success Modal -->

<!-- Error Modal -->
<div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Addition Result</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" role="alert">
                    Addition failed
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Error Modal -->

<!-- Friendship status no exist Modal -->
<div class="modal fade" id="noExistModal" tabindex="-1" aria-labelledby="noExistModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="noExistModalLabel">Addition Result</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" role="alert">
                    Friendship status no exist.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Friendship status no exist Modal -->

<!-- CSV Modal -->
<div class="modal fade" id="uploadCsvModal" tabindex="-1" aria-labelledby="uploadCsvModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="uploadCsvModalLabel">Import from CSV</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="csvUploadForm">
                    <div class="mb-3">
                        <label for="csvFile" class="form-label">CSV File</label>
                        <input type="file" class="form-control" id="csvFile" name="csvFile" accept=".csv" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="submitCsv()">Import</button>
            </div>
        </div>
    </div>
</div>
<!-- CSV Modal -->

<?php include '../parts/scripts.php' ?>
<script>
    const sendForm = e => {
    e.preventDefault();

    let isPassed = true;

    // 先清空可能已經存在的 Modal
    noExistModal.hide();

    // ... 其他驗證邏輯

    if (isPassed) {
        const fd = new FormData(document.form1);

        // 發送 POST 請求
        fetch('date_friendships_msg_add-api.php', {
            method: 'POST',
            body: fd,
        })
        .then(r => r.json())
        .then(result => {
            console.log({ result });

            if (result.success) {
                // 顯示成功消息
                myModal.show();
                // 清空表單
                document.form1.reset();
                // 移除所有驗證錯誤樣式和提示訊息
                clearValidationErrors();
                // 重置表單的 HTML5 驗證狀態
                // document.form1.classList.remove('was-validated');
            } else {
                // 如果是因為 Sender 和 Receiver 不對應到指定的 Friendship ID
                // 就顯示 noExistModal
                if (result.errorMismatch) {
                    noExistModal.show();
                } else {
                    // 其他錯誤，顯示 errorModal
                    errorModal.show();
                }
            }
        })
        .catch(ex => console.log(ex));
    }
};

    //check form
    document.getElementById("friendship_id").addEventListener("blur", checkFriendshipID);
    document.getElementById("sender_id").addEventListener("blur", checkSenderID);
    document.getElementById("receiver_id").addEventListener("blur", checkReceiverID);
    document.getElementById("content").addEventListener("blur", checkContent);

    function checkFriendshipID(){
        let friendshipIDEle = document.getElementById("friendship_id");
        let friendshipIDVal = friendshipIDEle.value.trim();//////取的id元素值（去空格)
        let friendshipIDLength = friendshipIDVal.length;
        let errorMsgFriendshipID =document.getElementById("friendshipID");

        if (friendshipIDVal === "") {
            errorMsgFriendshipID.innerHTML = `Cannot be blank.`;
        } else if (friendshipIDLength > 12) {
            errorMsgFriendshipID.innerHTML = `The maximum number of characters is 11`;
        } else if (!/^\d+$/.test(friendshipIDVal)) {
            errorMsgFriendshipID.innerHTML = `Must be a number.`;
        } else {
            errorMsgFriendshipID.innerHTML = ``;
        }
        return document.getElementById("friendshipID").innerHTML !== "";
    }

    function checkSenderID(){
        let senderIDEle = document.getElementById("sender_id");
        let senderIDVal = senderIDEle.value.trim();//////取的id元素值（去空格)
        let senderIDLength = senderIDVal.length;
        let errorMsgSender =document.getElementById("senderID");

        if (senderIDVal === "") {
            errorMsgSender.innerHTML = `Cannot be blank.`;
        } else if (senderIDLength > 12) {
            errorMsgSender.innerHTML = `The maximum number of characters is 11`;
        } else if (!/^\d+$/.test(senderIDVal)) {
            errorMsgSender.innerHTML = `Must be a number.`;
        } else {
            errorMsgSender.innerHTML = ``;
        }
        return document.getElementById("senderID").innerHTML !== "";
    }

    function checkReceiverID(){
        let receiverIDEle = document.getElementById("receiver_id");
        let receiverIDVal = receiverIDEle.value.trim();//////取的id元素值（去空格)
        let receiverIDLength = receiverIDVal.length;
        let errorMsgReceiver =document.getElementById("receiverID");

        if (receiverIDVal === "") {
            errorMsgReceiver.innerHTML = `Cannot be blank.`;
        } else if (receiverIDLength > 12) {
            errorMsgReceiver.innerHTML = `The maximum number of characters is 11`;
        } else if (!/^\d+$/.test(receiverIDVal)) {
            errorMsgReceiver.innerHTML = `Must be a number.`;
        } else {
            errorMsgReceiver.innerHTML = ``;
        }
        return document.getElementById("receiverID").innerHTML !== "";
    }

    function checkContent(){
        let contentIDEle = document.getElementById("content");
        let contentIDVal = contentIDEle.value.trim();//////取的id元素值（去空格)
        let errorMsgContent = document.getElementById("msgContent");

        if (contentIDVal === "") {
            errorMsgContent.innerHTML = `Cannot be blank.`;
        } else {
            errorMsgContent.innerHTML = ``;
        }
        return document.getElementById("msgContent").innerHTML !== "";
    }

    //submit CSV
    function submitCsv() {
        const formData = new FormData();
        const csvFile = document.getElementById('csvFile').files[0];

        if (!csvFile) {
            alert('Please select a CSV file to upload.');
            return;
        }

        formData.append('csvFile', csvFile);

        fetch('date_friendships_msg_import-csv-api.php', {
            method: 'POST',
            body: formData,
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                myModal.show();
            } else {
                errorModal.show();
            }
        })
        .catch((error) => {
            console.error('Error:', error);
            errorModal.show();
        });

        // Close CSV Modal
        const uploadCsvModal = bootstrap.Modal.getInstance(document.getElementById('uploadCsvModal'));
        uploadCsvModal.hide();
    }


    const myModal = new bootstrap.Modal(document.getElementById('exampleModal'));
    const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
    const noExistModal = new bootstrap.Modal(document.getElementById('noExistModal'));
</script>
<?php include '../parts/html-foot.php' ?>

/** Image filed is not required on edit page. Based on the page this set the image field is required or not */
var url = location.href.split("/").slice(-1)
var data = JSON.stringify(url)
var requiredFile = true;
if(data.slice(2,13) == 'EditProduct') {
    var requiredFile = false;
}


    $(document).ready(function() {
        var counter = 1;
        $("#addmore").click(() => {
            var imageContainer = `<div class="form-group row" id="image_container_${counter}">
            <div class="col-lg-5"></div>
	<div class="col-lg-5" >
    <input type="file" class="form-control imgAddmore" id="image_${counter}" name="image_addmore[]" value="">
	</div>
	<div class="col-lg-2">
    <img width="30" onclick="remove(${counter})"  id="addmore" height="30" src="img/remove.png" alt="remove" >	</div>
</div>`;
            $("#addmoreImage").append(imageContainer);
            counter++;
        });

    $("#addProduct").submit(function(event) {
        event.preventDefault();
        var saveButtonId = event.originalEvent.submitter.id;
        formData = $(this);
        validatefunc(formData, event);
    });
        return false;
    });

    function addMoreImageValidation() {
        $(".imgAddmore").each((event, index) => {
            $(event).rules("add", {
                required: true
            })

        });
    }

    function validatefunc(formData, event) {
        $.validator.addMethod('filesize', function(value, element, param) {
            return this.optional(element) || (element.files[0].size <= param)
        }, 'File size must be less than {0} kb');

        $("#addProduct").validate({
            rules: {
                category: {
                    required: true
                },
                sub_category: {
                    required: true
                },
                product: {
                    required: true,
                },
                catalog: {
                    required: requiredFile,
                    extension: 'pdf',
                    filesize: 1048576 //1 mb 1024*1024
                },
                "image_addmore[]": {
                    required: requiredFile,
                    extension: 'jpg|jpeg',
                    filesize: 2097152 //2 mb

                }
            },
            messages: {
                category: {
                    required: "Please select category"
                },
                sub_category: {
                    required: "Please select sub category"
                },
                product: {
                    required: "Product name is required"
                },
                catalog: {
                    required: "Catalog file is required",
                    extesion: "Please upload pdf file"
                },
                "image_addmore[]": {
                    required: "Image is required",
                    extension: "Please upload jpg file"
                },
            },

            submitHandler: function(formData, event) {
                var formData = new FormData(formData);
                event.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: "process.php",
                    data: formData,
                    dataType: "json",
                    encode: true,
                    enctype: 'multipart/form-data',
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        const form = document.querySelector('form');
                        if (event.originalEvent.submitter.id === 'save') {
                           $("#exampleModal").modal('hide');
                            location.reload();
                            window.close();
                            window.opener.location.reload();
                            } else {
                            form.reset();
                        }
                    },
                    error: function(error, errorCode, message) {
                        console.log(message);
                    }

                })
            }
        });

    }


    /**
     * 
     * This function remove the image element from html dom
     */
    function remove(id) {
        $("#image_container_" + id).remove();
    }

    function deleteImage(id) {
        if(confirm("Are you sure to delete this image?") == false) return false;
        $.ajax({
            url: 'process.php?imageId='+id,
            type: 'POST',
            success: function(response){
                remove(id);
            }
        });

    }

    function deleteProduct(id) {
        if(confirm("Are you sure to delete product?") == false) return false;
        $.ajax({
            url: 'process.php?productId='+id,
            type: 'POST',
            success: function(response){
                console.log(response);
                $("#product_container_"+id).remove();
            }
        });

    }

    function subCategoryList(selectCategory) {
        var id = selectCategory.value;
        $.ajax({
            url: 'process.php?categoryId='+id,
            type: 'POST',
            success: function(response){
                $("#subCatList").html(JSON.parse(response));
            },
            error: function(error, errorCode, message) {
                console.log(message);
            }
        });

    }
function removeImage() {

    if(this.className !== "image-with-cross newly-uploaded") {

        if(this.className === "image-with-cross image-deleted") {
             
            this.className = "image-with-cross";
            this.children[0].setAttribute("disabled","disabled")

            if(this.id === "thumbnail-image") {
                document.getElementById("hidden-thumbnail-upload").style.display = "none";

                const otherThumbnails = this.parentElement.getElementsByClassName("newly-uploaded"); 

                if(otherThumbnails.length > 0) {
                    this.parentElement.removeChild(otherThumbnails[0])
                }
               
            }

        } else {

            this.className = "image-with-cross image-deleted";
            this.children[0].removeAttribute("disabled")

            if(this.id === "thumbnail-image") {
                document.getElementById("hidden-thumbnail-upload").style.display = "block";
            }

        }

    } else {

        if(this.parentElement.id === "thumbnail-output") {
            document.getElementById("hidden-thumbnail-upload").style.display = "block";
        }


        //If element is newly uploaded remove it
        this.parentElement.removeChild(this);


        if(this.id === "thumbnail-image") {
            document.getElementById("hidden-thumbnail-upload").style.display = "block";
        }
    }

}

function instantUpload(event) {
    const reader = new FileReader();

    const fileUploadInput = this;
    const file = this.files[0];
    const imageOutput = event.currentTarget.myParameter;

    reader.onload = function(event) {
        
        const imageSrc = "data:" + file.type + ";base64," + btoa(event.target.result);

        document.getElementById(imageOutput).appendChild(createImageWithCross(imageSrc,fileUploadInput));
         
        setDeleteEvents();

        if(imageOutput === "thumbnail-output") {

            document.getElementById("hidden-thumbnail-upload").style.display = "none";

        }  
        
        fileUploadInput.value = "";
        fileUploadInput.files = (document.createElement("input").type = "file").files;

    }

    reader.readAsBinaryString(file);
}

function createImageWithCross(imageSrc, fileUploadInput) {

    const tempFileUpload = document.createElement("input");
    tempFileUpload.type = "file";
    tempFileUpload.files = fileUploadInput.files;
    tempFileUpload.name = fileUploadInput.name;
    tempFileUpload.style.display = "none";

    const image = document.createElement("img");
    image.className = "newly-uploaded";
    image.src = imageSrc;

    const imageWithCross = document.createElement("div");
    imageWithCross.className = "image-with-cross newly-uploaded";
    imageWithCross.appendChild(image);
    imageWithCross.appendChild(tempFileUpload);

    return imageWithCross;
}

function setDeleteEvents() {
    // Set an event to each .close element
    for(let index in (closeClasses = document.getElementsByClassName("image-with-cross"))) {

        if(isNaN(index)) {
            continue;
        }

        closeClasses[index].removeEventListener("click", removeImage);
        closeClasses[index].addEventListener("click", removeImage);
    }
}

setDeleteEvents();

instantImageUpload = document.getElementsByClassName("instant-image-upload");

instantImageUpload[0].addEventListener("change", instantUpload, false);
instantImageUpload[0].myParameter = "thumbnail-output";
instantImageUpload[1].addEventListener("change", instantUpload, false);
instantImageUpload[1].myParameter = "image-output";

// On submit clean up any existing placeholder file uploads
document.getElementsByTagName("form")[0].addEventListener("submit", function() {

    const placeholderFileUploads = document.getElementsByClassName("instant-image-upload");

    for(let index in placeholderFileUploads) {
        placeholderFileUploads[index].name = "";
    }

});
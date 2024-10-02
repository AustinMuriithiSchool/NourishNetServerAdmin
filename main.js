/*service section*/
$(document).ready(function () {
    $(".buttons").click(function () {
        $(this).addClass("active").siblings().removeClass("active");

        var filter = $(this).attr("data-filter");
        if (filter == "all") {
            $(".box").show();
        } else {
            $(".box").not("." + filter).hide();
            $(".box").filter("." + filter).show();
        }
    });
});
/*servive section ends*/

/*review section*/
var swiper=new Swiper('.review-slider',{
    loop:true,
    grabCursor:true,
    spaceBetween:20,
    breakpoints:{
        0:{
            slidesPerView:1
        },
        640:{
            slidesPerView:2
        },
        768:{
            slidesPerView:3
        },
    },
});
/*review section ends*/

// Get the form element
const uploadForm = document.getElementById('uploadForm');

// Add event listener for form submission
uploadForm.addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent form submission

    // Assuming you have successfully handled the form submission, clear the form fields
    uploadForm.reset(); // Reset the form to clear input fields

    // Optionally, you can provide feedback to the user that the upload was successful
    alert('Food item uploaded successfully!');
});


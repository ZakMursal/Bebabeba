// // Controll User Sidebar 

// // Get the elements for user icon and cart icon
// var userIcon = document.querySelector('.fas.fa-user');
// // var cartIcon = document.querySelector('.fas.fa-shopping-cart'); // Uncomment if you have a cart icon

// // Get the sidebar elements
// var userSidebar = document.querySelector('.user-sidebar');
// // var cartSidebar = document.querySelector('.cart-sidebar'); // Uncomment if you have a cart sidebar

// // Add event listeners to the user icon and cart icon
// userIcon.addEventListener('click', function() {
//     userSidebar.classList.toggle('active');
//     // cartSidebar.classList.remove('active'); // Uncomment if you have a cart sidebar
// });

// function closeUserSidebar() {
//     userSidebar.classList.remove('active'); // Hide the user sidebar
// }

function openUserSidebar() {
            document.getElementById("userSidebar").style.display = "block";
        }
    
        function closeUserSidebar() {
            document.getElementById("userSidebar").style.display = "none";
        }
// Date section 

function updateDateTime() {
    let date = new Date();
    let hours = date.getHours();
    let minutes = date.getMinutes();
    let suffix = "AM";

    if (hours >= 12) {
        suffix = "PM";
        if (hours > 12) {
            hours -= 12;
        }
    } else if (hours === 0) {
        hours = 12;
    }

    if (minutes < 10) {
        minutes = "0" + minutes;
    }

    let day = date.getDate();
    let month = date.toLocaleString('default', { month: 'long' });
    let year = date.getFullYear();

    let formattedDate = `${month} ${day}, ${year}`;
    let formattedTime = `${hours}:${minutes} ${suffix}`;

    document.getElementById("date").innerHTML = `${formattedDate} - ${formattedTime}`;
}

// Call the function to update the date and time immediately
updateDateTime();

// Set the interval to update the date and time every 60 seconds
setInterval(updateDateTime, 60000);

/*-------------------------------------------------------------
Check if no cources are available  -- instrucor-panel.php
----------------------------------------------------------------*/
const coursesContainer = document.querySelector('.courses');
const courses = coursesContainer.querySelectorAll('.course');

if (courses.length === 0) {
    const noCoursesDiv = document.createElement('div');
    noCoursesDiv.classList.add('course', 'no-courses');
    const message = document.createElement('p');
    message.classList.add('course-title');
    message.textContent = "You Don't Have Any Courses. Add to see courses here";
    noCoursesDiv.appendChild(message);
    coursesContainer.appendChild(noCoursesDiv);
}

/*-------------------------------------------------------------
Show form when + is tapped  -- instrucor-panel.php
----------------------------------------------------------------*/
document.querySelector(".add-crs").addEventListener("click", function() {
    document.querySelector(".add-course-form").style.display = "flex";
});

document.querySelector(".add-crs").addEventListener("click", function() {
    // Create overlay if it doesn't exist
    let overlay = document.querySelector(".custom-overlay");
    if (!overlay) {
        overlay = document.createElement("div");
        overlay.classList.add("custom-overlay");
        document.body.appendChild(overlay);
        
        // Add click event to remove overlay and hide form
        overlay.addEventListener("click", function() {
            document.querySelector(".add-course-form").style.display = "none";
            overlay.remove();
        });
    }

    // Show form and overlay
    document.querySelector(".add-course-form").style.display = "flex";
    overlay.style.display = "block";
});

/*-------------------------------------------------
Edit form
---------------------------------------------------*/

document.querySelector(".info-edit").addEventListener("click", function() {
    document.querySelector(".edit-user-form").style.display = "flex";
});

document.querySelector(".info-edit").addEventListener("click", function() {
    // Create overlay if it doesn't exist
    let overlay = document.querySelector(".custom-overlay");
    if (!overlay) {
        overlay = document.createElement("div");
        overlay.classList.add("custom-overlay");
        document.body.appendChild(overlay);
        
        // Add click event to remove overlay and hide form
        overlay.addEventListener("click", function() {
            document.querySelector(".edit-user-form").style.display = "none";
            overlay.remove();
        });
    }

    // Show form and overlay
    document.querySelector(".edit-user-form").style.display = "flex";
    overlay.style.display = "block";
});



// Add styles dynamically for the overlay
const style = document.createElement("style");
style.innerHTML = `
    .custom-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        z-index: 1000;
        display: none;
    }
    .add-course-form,.edit-user-form {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        padding: 20px;
        z-index: 1001;
        border-radius: 5px;
    }
`;
document.head.appendChild(style);



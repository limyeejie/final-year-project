<?php
include 'modal.php';

include '../config/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $volunteerId = $_POST['editVolunteerId'];

    // Get the form data
    $volunteerName = htmlspecialchars($_POST['editVolunteerName']);
    $organizerContact = htmlspecialchars($_POST['organizerContact']);
    $organizerName = htmlspecialchars($_POST['editOrganizerName']);
    $eventTime = htmlspecialchars($_POST['editVolunteerTime']);
    $organizerPhone = htmlspecialchars($_POST['organizerPhone']);
    $volunteerDate = htmlspecialchars($_POST['editVolunteerDate']);
    $volunteerLocation = htmlspecialchars($_POST['editVolunteerLocation']);
    $volunteerDescription = htmlspecialchars(string: $_POST['editVolunteerDescription']);
    $volunteerRequirement = htmlspecialchars($_POST['editVolunteerRequirement']);
    $maxVolunteers = isset($_POST['editMaxVolunteers']) ? (int)$_POST['editMaxVolunteers'] : 0;

    $currentDate = date('Y-m-d');
    if ($volunteerDate < $currentDate) {
        //echo "<script>alert('The event date cannot be in the past. Please select a valid date.'); window.history.back();</script>";
        $redirectUrl = 'management.php';
        echo "<script>
        document.querySelector('#failureModal .modal-close-btn')
            .setAttribute('onclick', `closeModal('failureModal', '$redirectUrl')`);
        showModal('failure', 'The event date cannot be in the past. Please select a valid date.');
        </script>";
        exit();
    }
    if (empty($volunteerName)) {
        //echo "<script>alert('Event location is required.'); window.history.back();</script>";
        $redirectUrl = 'management.php';
        echo "<script>
        document.querySelector('#failureModal .modal-close-btn')
            .setAttribute('onclick', `closeModal('failureModal', '$redirectUrl')`);
        showModal('failure', 'Event location is required.');
        </script>";
        exit();
    } elseif (!preg_match("/^[a-zA-Z\s,.'-]+$/", $volunteerName)) {
        //echo "<script>alert('Event location can only contain letters, spaces, and basic punctuation (, . ' -).'); window.history.back();</script>";
        $redirectUrl = 'management.php';
        echo "<script>
        document.querySelector('#failureModal .modal-close-btn')
            .setAttribute('onclick', `closeModal('failureModal', '$redirectUrl')`);
        showModal('failure', 'Event location can only contain letters, spaces, and basic punctuation (, . \' -).');
        </script>";
        exit();
    }

    if (empty($organizerPhone)) {
        //echo "<script>alert('Organizer phone number is required.'); window.history.back();</script>";
        $redirectUrl = 'management.php';
        echo "<script>
        document.querySelector('#failureModal .modal-close-btn')
            .setAttribute('onclick', `closeModal('failureModal', '$redirectUrl')`);
        showModal('failure', 'Organizer phone number is required.');
        </script>";
        exit();
    } elseif (!preg_match("/^\+60\d{9,10}$/", $organizerPhone)) {
        //echo "<script>alert('Organizer phone number must start with +60 and be followed by 9 or 10 digits.'); window.history.back();</script>";
        $redirectUrl = 'management.php';
        echo "<script>
        document.querySelector('#failureModal .modal-close-btn')
            .setAttribute('onclick', `closeModal('failureModal', '$redirectUrl')`);
        showModal('failure', 'Organizer phone number must start with +60 and be followed by 9 or 10 digits.');
        </script>";
        exit();
    }
    
    
    if ($maxVolunteers < 1) {
        //echo "<script>alert('The maximum number of participants must be greater than 1.'); window.history.back();</script>";
        $redirectUrl = 'management.php';
        echo "<script>
        document.querySelector('#failureModal .modal-close-btn')
            .setAttribute('onclick', `closeModal('failureModal', '$redirectUrl')`);
        showModal('failure', 'The maximum number of participants must be greater than 1.');
        </script>";
        exit();
    }


    if (empty($eventTime)) {
        //echo "<script>alert('Event time is required.'); window.history.back();</script>";
        $redirectUrl = 'management.php';
        echo "<script>
        document.querySelector('#failureModal .modal-close-btn')
            .setAttribute('onclick', `closeModal('failureModal', '$redirectUrl')`);
        showModal('failure', 'Event time is required');
        </script>";
        exit();
    } elseif (!preg_match("/^(?:2[0-3]|[01]?[0-9]):[0-5][0-9]$/", $eventTime)) {
        //echo "<script>alert('Event time must be in the format HH:MM (24-hour format).'); window.history.back();</script>";
        $redirectUrl = 'management.php';
        echo "<script>
        document.querySelector('#failureModal .modal-close-btn')
            .setAttribute('onclick', `closeModal('failureModal', '$redirectUrl')`);
        showModal('failure', 'Event time must be in the format HH:MM (24-hour format).');
        </script>";
        exit();
    }


    if (empty($organizerContact)) {
        //echo "<script>alert('Organizer contact email is required.'); window.history.back();</script>";
        $redirectUrl = 'management.php';
        echo "<script>
        document.querySelector('#failureModal .modal-close-btn')
            .setAttribute('onclick', `closeModal('failureModal', '$redirectUrl')`);
        showModal('failure', 'Organizer contact email is required.');
        </script>";
        exit();
    } elseif (!filter_var($organizerContact, FILTER_VALIDATE_EMAIL)) {
        //echo "<script>alert('Invalid email format for organizer contact.'); window.history.back();</script>";
        $redirectUrl = 'management.php';
        echo "<script>
        document.querySelector('#failureModal .modal-close-btn')
            .setAttribute('onclick', `closeModal('failureModal', '$redirectUrl')`);
        showModal('failure', 'Invalid email format for organizer contact.');
        </script>";
        exit();
    }

    if (empty($volunteerDescription)) {
        //echo "<script>alert('Event description is required.'); window.history.back();</script>";
        $redirectUrl = 'management.php';
        echo "<script>
        document.querySelector('#failureModal .modal-close-btn')
            .setAttribute('onclick', `closeModal('failureModal', '$redirectUrl')`);
        showModal('failure', 'Event description is required.');
        </script>";
        exit();
    } elseif (!preg_match("/^[a-zA-Z\s,.'-]+$/", $volunteerDescription)) {
        //echo "<script>alert('Event description can only contain letters, spaces, and basic punctuation (, . ' -).'); window.history.back();</script>";
        $redirectUrl = 'management.php';
        echo "<script>
        document.querySelector('#failureModal .modal-close-btn')
            .setAttribute('onclick', `closeModal('failureModal', '$redirectUrl')`);
        showModal('failure', 'Event description can only contain letters, spaces, and basic punctuation (, . \' -).');
        </script>";
        exit();
    }

    if (empty($volunteerLocation)) {
        //echo "<script>alert('Event location is required.'); window.history.back();</script>";
        $redirectUrl = 'management.php';
        echo "<script>
        document.querySelector('#failureModal .modal-close-btn')
            .setAttribute('onclick', `closeModal('failureModal', '$redirectUrl')`);
        showModal('failure', 'Event location is required.');
        </script>";
        exit();
    } elseif (!preg_match("/^[a-zA-Z\s,.'-]+$/", $volunteerLocation)) {
        //echo "<script>alert('Event location can only contain letters, spaces, and basic punctuation (, . ' -).'); window.history.back();</script>";
        $redirectUrl = 'management.php';
        echo "<script>
        document.querySelector('#failureModal .modal-close-btn')
            .setAttribute('onclick', `closeModal('failureModal', '$redirectUrl')`);
        showModal('failure', 'Event location can only contain letters, spaces, and basic punctuation (, . \' -).');
        </script>";
        exit();
    }
    
    if (empty($organizerName)) {
        //echo "<script>alert('Organizer name is required.'); window.history.back();</script>";
        $redirectUrl = 'management.php';
        echo "<script>
        document.querySelector('#failureModal .modal-close-btn')
            .setAttribute('onclick', `closeModal('failureModal', '$redirectUrl')`);
        showModal('failure', 'Organizer name is required.');
        </script>";
        exit();
    } elseif (!preg_match("/^[a-zA-Z\s'-]+$/", $organizerName)) {
        //echo "<script>alert('Organizer name can only contain letters, spaces, hyphens, and apostrophes.'); window.history.back();</script>";
        $redirectUrl = 'management.php';
        echo "<script>
        document.querySelector('#failureModal .modal-close-btn')
            .setAttribute('onclick', `closeModal('failureModal', '$redirectUrl')`);
        showModal('failure', 'Organizer name can only contain letters, spaces, hyphens, and apostrophes.');
        </script>";
        exit();
    }


    $existingImage = isset($_POST['existingVolunteerImages']) ? base64_decode($_POST['existingVolunteerImages']) : null;

    if (isset($_FILES['editVolunteerImage']) && $_FILES['editVolunteerImage']['error'] === UPLOAD_ERR_OK) {
        // Get the temporary file path of the uploaded file
        $uploadedFile = $_FILES['editVolunteerImage']['tmp_name'];

        // Get the file's binary data
        $imageData = file_get_contents($uploadedFile);

        // Prepare the image data for the database
        $volunteerImage = $imageData; // Store this as LONG BLOB in the database

        $sql = "UPDATE volunteers SET image = ?, title = ?, organizer_name = ?, organizer_number = ?, event_time = ?, organizer_contact = ?, date = ?, location = ?, description = ?, requirement = ?, max_volunteer = ? WHERE id = ?";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param('sssssssssssi', $volunteerImage, $volunteerName, $organizerName, $organizerPhone, $eventTime, $organizerContact, $volunteerDate, $volunteerLocation, $volunteerDescription, $volunteerRequirement, $maxVolunteers, $volunteerId);

            if ($imageData !== null) {
                $stmt->send_long_data(0, $volunteerImage);  // 0 is the position of the image parameter
            }

            if ($stmt->execute()) {
                //echo "<script>alert('Volunteer event edited successfully!'); window.location.href = 'management.php';</script>";
                $redirectUrl = 'management.php';
                echo "<script>
                    document.querySelector('#successModal .modal-close-btn')
                        .setAttribute('onclick', `closeModal('successModal', '$redirectUrl')`);
                    showModal('success', 'You have succesfully edited the volunteer event!');
                </script>";
            } else {
                echo "Error: Could not execute the query." . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Error: Could not prepare the query. " . $conn->error;
        }

        $conn->close();
    } else {
        // Update without changing the image
        $sql = "UPDATE volunteers SET title = ?, organizer_name = ?, organizer_number = ?, event_time = ?, organizer_contact = ?, date = ?, location = ?, description = ?, requirement = ?, max_volunteer = ? WHERE id = ?";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param('ssssssssssi', $volunteerName, $organizerName, $organizerPhone, $eventTime, $organizerContact, $volunteerDate, $volunteerLocation, $volunteerDescription, $volunteerRequirement, $maxVolunteers, $volunteerId);

            if ($stmt->execute()) {
                //echo "<script>alert('Volunteer event edited successfully!'); window.location.href = 'management.php';</script>";
                $redirectUrl = 'management.php';
                echo "<script>
                    document.querySelector('#successModal .modal-close-btn')
                        .setAttribute('onclick', `closeModal('successModal', '$redirectUrl')`);
                    showModal('success', 'You have succesfully edited the volunteer event!');
                </script>";
            } else {
                echo "Error: Could not execute the query." . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Error: Could not prepare the query. " . $conn->error;
        }

        $conn->close();
    }
}
?>

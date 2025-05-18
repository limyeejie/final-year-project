<?php
include '../config/connection.php';
include 'modal.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $eventId = $_POST['editEventId'];

    // Get the form data
    $eventName = htmlspecialchars($_POST['editEventName']);
    $organizerContact = htmlspecialchars($_POST['organizerContact']);
    $organizerName = htmlspecialchars($_POST['editOrganizerName']);
    $eventTime = htmlspecialchars($_POST['editEventTime']);
    $organizerPhone = htmlspecialchars($_POST['organizerPhone']);
    $eventDate = htmlspecialchars($_POST['editEventDate']);
    $startRegDate = htmlspecialchars($_POST['startRegDate']);
    $dueRegDate = htmlspecialchars($_POST['dueRegDate']);
    $eventLocation = htmlspecialchars($_POST['editEventLocation']);
    $eventDescription = htmlspecialchars($_POST['editEventDescription']);
    $eventCategory = htmlspecialchars($_POST['eventCategory']);
    $maxParticipants = isset($_POST['editMaxParticipants']) ? (int)$_POST['editMaxParticipants'] : 0;

    $existingImage = isset($_POST['existingEventImages']) ? base64_decode($_POST['existingEventImages']) : null;

    $currentDate = date('Y-m-d');
    if ($eventDate < $currentDate) {
        //echo "<script>alert('The event date cannot be in the past. Please select a valid date.'); window.history.back();</script>";
        $redirectUrl = 'management.php';
        echo "<script>
        document.querySelector('#failureModal .modal-close-btn')
            .setAttribute('onclick', `closeModal('failureModal', '$redirectUrl')`);
        showModal('failure', 'The event date cannot be in the past. Please select a valid date.');
        </script>";
        exit();
    }
    if (empty($eventName)) {
        //echo "<script>alert('Event location is required.'); window.history.back();</script>";
        $redirectUrl = 'management.php';
        echo "<script>
        document.querySelector('#failureModal .modal-close-btn')
            .setAttribute('onclick', `closeModal('failureModal', '$redirectUrl')`);
        showModal('failure', 'Event location is required.');
        </script>";
        exit();
    } elseif (!preg_match("/^[a-zA-Z\s,.'-]+$/", $eventName)) {
        //echo "<script>alert('Event location can only contain letters, spaces, and basic punctuation (, . ' -).'); window.history.back();</script>";
        $redirectUrl = 'management.php';
        echo "<script>
        document.querySelector('#failureModal .modal-close-btn')
            .setAttribute('onclick', `closeModal('failureModal', '$redirectUrl')`);
        showModal('failure', 'Event location can only contain letters, spaces, and basic punctuation (, . \' -).');
        </script>";
        exit();
    }

    if ($maxParticipants < 1) {
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
    
    if (empty($eventDescription)) {
        //echo "<script>alert('Event description is required.'); window.history.back();</script>";
        $redirectUrl = 'management.php';
        echo "<script>
        document.querySelector('#failureModal .modal-close-btn')
            .setAttribute('onclick', `closeModal('failureModal', '$redirectUrl')`);
        showModal('failure', 'Event description is required.');
        </script>";
        exit();
    } elseif (!preg_match("/^[a-zA-Z\s,.'-]+$/", $eventDescription)) {
        //echo "<script>alert('Event description can only contain letters, spaces, and basic punctuation (, . ' -).'); window.history.back();</script>";
        $redirectUrl = 'management.php';
        echo "<script>
        document.querySelector('#failureModal .modal-close-btn')
            .setAttribute('onclick', `closeModal('failureModal', '$redirectUrl')`);
        showModal('failure', 'Event description can only contain letters, spaces, and basic punctuation (, . \' -).');
        </script>";
        exit();
    }

    if (empty($eventLocation)) {
        //echo "<script>alert('Event location is required.'); window.history.back();</script>";
        $redirectUrl = 'management.php';
        echo "<script>
        document.querySelector('#failureModal .modal-close-btn')
            .setAttribute('onclick', `closeModal('failureModal', '$redirectUrl')`);
        showModal('failure', 'Event location is required.');
        </script>";
        exit();
    } elseif (!preg_match("/^[a-zA-Z\s,.'-]+$/", $eventLocation)) {
        //echo "<script>alert('Event location can only contain letters, spaces, and basic punctuation (, . ' -).'); window.history.back();</script>";
        $redirectUrl = 'management.php';
        echo "<script>
        document.querySelector('#failureModal .modal-close-btn')
            .setAttribute('onclick', `closeModal('failureModal', '$redirectUrl')`);
        showModal('failure', 'Event location can only contain letters, spaces, and basic punctuation (, . \' -).');
        </script>";
        exit();
    }

    if (isset($_FILES['editEventImage']) && $_FILES['editEventImage']['error'] === UPLOAD_ERR_OK) {
        // Get the temporary file path of the uploaded file
        $uploadedFile = $_FILES['editEventImage']['tmp_name'];
        
        // Get the file's binary data
        $imageData = file_get_contents($uploadedFile);
        
        // Prepare the image data for the database
        $eventImage = $imageData; // Store this as LONG BLOB in the database

        $sql = "UPDATE events SET image = ?, title = ?, organizer_name = ?, organizer_number = ?, event_time = ?, organizer_contact = ?, date = ?, start_registration_date = ?, due_registration_date = ?, location = ?, description = ?, event_category = ?, max_participants = ? WHERE id = ?";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param('sssssssssssssi', $eventImage, $eventName, $organizerName, $organizerPhone, $eventTime, $organizerContact, $eventDate, $startRegDate, $dueRegDate, $eventLocation, $eventDescription, $eventCategory, $maxParticipants, $eventId);
    
            if ($imageData !== null) {
                $stmt->send_long_data(0, $eventImage);  // 0 is the position of the image parameter
            }
    
            if ($stmt->execute()) {
                //echo "<script>alert('Event edited successfully!'); window.location.href = 'management.php';</script>";
                $redirectUrl = 'management.php';
                echo "<script>
                    document.querySelector('#successModal .modal-close-btn')
                        .setAttribute('onclick', `closeModal('successModal', '$redirectUrl')`);
                    showModal('success', 'You have succesfully edited the participation event!');
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

        $sql = "UPDATE events SET title = ?, organizer_name = ?, organizer_number = ?, event_time = ?, organizer_contact = ?, date = ?, start_registration_date = ?, due_registration_date = ?, location = ?, description = ?, event_category = ?, max_participants = ? WHERE id = ?";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param('ssssssssssssi',$eventName,$organizerName, $organizerPhone, $eventTime, $organizerContact, $eventDate, $startRegDate, $dueRegDate, $eventLocation, $eventDescription, $eventCategory, $maxParticipants, $eventId);
    
            if ($stmt->execute()) {
                //echo "<script>alert('Event edited successfully!'); window.location.href = 'management.php';</script>";
                $redirectUrl = 'management.php';
                echo "<script>
                    document.querySelector('#successModal .modal-close-btn')
                        .setAttribute('onclick', `closeModal('successModal', '$redirectUrl')`);
                    showModal('success', 'You have succesfully edited the participation event!');
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
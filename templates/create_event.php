<?php
include '../config/connection.php';
include 'modal.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $eventName = htmlspecialchars($_POST['eventName']);
    $organizerName = htmlspecialchars($_POST['organizerName']);
    $organizerContact = htmlspecialchars($_POST['organizerContact']);
    $organizerPhone = htmlspecialchars($_POST['organizerPhone']);
    $eventTime = htmlspecialchars($_POST['eventTime']);
    $eventDate = htmlspecialchars($_POST['eventDate']);
    $startRegDate = htmlspecialchars($_POST['startRegDate']);
    $dueRegDate = htmlspecialchars($_POST['dueRegDate']);
    $eventLocation = htmlspecialchars($_POST['eventLocation']);
    $eventDescription = htmlspecialchars($_POST['eventDescription']);
    $eventCategory = htmlspecialchars($_POST['eventCategory']);
    $maxParticipants = isset($_POST['maxParticipants']) ? (int)$_POST['maxParticipants'] : 0;

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

    if ($startRegDate < $currentDate) {
        //echo "<script>alert('The start registration date cannot be in the past. Please select a valid date.'); window.history.back();</script>";
        $redirectUrl = 'management.php';
        echo "<script>
        document.querySelector('#failureModal .modal-close-btn')
            .setAttribute('onclick', `closeModal('failureModal', '$redirectUrl')`);
        showModal('failure', 'The start registration date cannot be in the past. Please select a valid date.');
        </script>";
        exit();
    }
    if ($dueRegDate < $currentDate) {
        //echo "<script>alert('The due registration date cannot be in the past. Please select a valid date.'); window.history.back();</script>";
        $redirectUrl = 'management.php';
        echo "<script>
        document.querySelector('#failureModal .modal-close-btn')
            .setAttribute('onclick', `closeModal('failureModal', '$redirectUrl')`);
        showModal('failure', 'The due registration date cannot be in the past. Please select a valid date.');
        </script>";
        exit();
    }
    if ($dueRegDate < $startRegDate) {
        //echo "<script>alert('The due registration date cannot be before the start registration date. Please select valid dates.'); window.history.back();</script>";
        $redirectUrl = 'management.php';
        echo "<script>
        document.querySelector('#failureModal .modal-close-btn')
            .setAttribute('onclick', `closeModal('failureModal', '$redirectUrl')`);
        showModal('failure', 'The due registration date cannot be before the start registration date. Please select valid dates.');
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

    if (isset($_FILES['eventImage']) && $_FILES['eventImage']['error'] === UPLOAD_ERR_OK) {
        $uploadedFile = $_FILES['eventImage']['tmp_name'];

        $imageData = file_get_contents($uploadedFile);

        $sql = "INSERT INTO events (image, title, organizer_name, organizer_number, event_time, organizer_contact, date, start_registration_date, due_registration_date, location, description, event_category, max_participants) VALUES  (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param('ssssssssssssi', $imageData, $eventName, $organizerName, $organizerPhone, $eventTime, $organizerContact, $eventDate, $startRegDate, $dueRegDate, $eventLocation, $eventDescription, $eventCategory, $maxParticipants);

            if ($imageData == null) {
                $stmt->send_long_data(0, $imageData);
            }

            if($stmt->execute()) {
                
                $notificationMessage = "A new event '$eventName' has been created! Check it out.";
                $notificationSql = "INSERT INTO notifications (user_id, message, time, read_status)
                                    SELECT id, ?, NOW(), 0 
                                    FROM users 
                                    WHERE role = 'Student'";
                if ($notificationStmt = $conn->prepare($notificationSql)) {
                    $notificationStmt->bind_param("s", $notificationMessage);
                    $notificationStmt->execute();
                    $notificationStmt->close();

                } else {
                    echo "Error: Could not prepare the notification query. " . $conn->error;
                }
                
                $redirectUrl = 'management.php';
                echo "<script>
                    document.querySelector('#successModal .modal-close-btn')
                        .setAttribute('onclick', `closeModal('successModal', '$redirectUrl')`);
                    showModal('success', 'You have succesfully created a participation event!');
                </script>";
                exit();
            } else {
                echo "Error: Could not execute the query." . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Error: Could not prepare the query. " . $conn->error;
        }

    } else {
        $sql = "INSERT INTO events (title, organizer_name, organizer_number, event_time, organizer_contact, date, start_registration_date, due_registration_date, location, description, event_category, max_participants) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param('sssssssssssi', $eventName,$organizerName, $organizerPhone, $eventTime, $organizerContact, $eventDate, $startRegDate, $dueRegDate, $eventLocation, $eventDescription, $eventCategory, $maxParticipants);

            if($stmt->execute()) {
                
                $notificationMessage = "A new event '$eventName' has been created! Check it out.";
                $notificationSql = "INSERT INTO notifications (user_id, message, time, read_status)
                                    SELECT id, ?, NOW(), 0 
                                    FROM users 
                                    WHERE role = 'Student'";
                if ($notificationStmt = $conn->prepare($notificationSql)) {
                    $notificationStmt->bind_param("s", $notificationMessage);
                    $notificationStmt->execute();
                    $notificationStmt->close();
                } else {
                    echo "Error: Could not prepare the notification query. " . $conn->error;
                }
                $redirectUrl = 'management.php';
                echo "<script>
                    document.querySelector('#successModal .modal-close-btn')
                        .setAttribute('onclick', `closeModal('successModal', '$redirectUrl')`);
                    showModal('success', 'You have succesfully created a participation event!');
                </script>";
                exit();
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
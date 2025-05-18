<?php
include '../config/connection.php';
include 'modal.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $volunteerName = htmlspecialchars($_POST['volunteerName']);
    $organizerName = htmlspecialchars($_POST['organizerName']);
    $organizerContact = htmlspecialchars($_POST['organizerContact']);
    $organizerPhone = htmlspecialchars($_POST['organizerPhone']);
    $volunteerDate = htmlspecialchars($_POST['volunteerDate']);
    $volunteerTime = htmlspecialchars($_POST['volunteerTime']);
    $volunteerLocation = htmlspecialchars($_POST['volunteerLocation']);
    $volunteerDescription = htmlspecialchars($_POST['volunteerDescription']);
    $volunteerRequirement = htmlspecialchars($_POST['volunteerRequirement']);
    $maxVolunteer = isset($_POST['maxVolunteer']) ? (int)$_POST['maxVolunteer'] : 0;


    $currentDate = date('Y-m-d');

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

    if ($maxVolunteer < 1) {
        //echo "<script>alert('The maximum number of participants must be greater than 1.'); window.history.back();</script>";
        $redirectUrl = 'management.php';
        echo "<script>
        document.querySelector('#failureModal .modal-close-btn')
            .setAttribute('onclick', `closeModal('failureModal', '$redirectUrl')`);
        showModal('failure', 'The maximum number of participants must be greater than 1.');
        </script>";
        exit();
    }

    if (empty($volunteerTime)) {
        //echo "<script>alert('Event time is required.'); window.history.back();</script>";
        $redirectUrl = 'management.php';
        echo "<script>
        document.querySelector('#failureModal .modal-close-btn')
            .setAttribute('onclick', `closeModal('failureModal', '$redirectUrl')`);
        showModal('failure', 'Event time is required');
        </script>";
        exit();
    } elseif (!preg_match("/^(?:2[0-3]|[01]?[0-9]):[0-5][0-9]$/", $volunteerTime)) {
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

    // Validate eventDescription
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
        showModal('failure', ''Event location can only contain letters, spaces, and basic punctuation (, . \' -).');
        </script>";
        exit();
    }
    

    if (isset($_FILES['volunteerImage']) && $_FILES['volunteerImage']['error'] === UPLOAD_ERR_OK) {
        $uploadedFile = $_FILES['volunteerImage']['tmp_name'];

        $imageData = file_get_contents($uploadedFile);

        $sql = "INSERT INTO volunteers (image, title, organizer_name, organizer_number, event_time, organizer_contact, date, location, description, requirement, max_volunteer) VALUES  (?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param('ssssssssssi',$imageData, $volunteerName, $organizerName, $organizerPhone, $volunteerTime, $organizerContact, $volunteerDate, $volunteerLocation, $volunteerDescription, $volunteerRequirement, $maxVolunteer);

            if ($imageData == null) {
                $stmt->send_long_data(0, $imageData);
            }

            if($stmt->execute()) {
                $notificationMessage = "A new volunteer event '$volunteerName' has been created! Check it out.";
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
                    showModal('success', 'You have succesfully created a volunteer event!');
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
        $sql = "INSERT INTO volunteers (title, organizer_name, organizer_number, event_time, organizer_contact, date, location, description, requirement, max_volunteer) VALUES  (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param('sssssssssi', $volunteerName,$organizerName, $organizerPhone, $volunteerTime, $organizerContact, $volunteerDate, $volunteerLocation, $volunteerDescription, $volunteerRequirement, $maxVolunteer);

            if ($stmt->execute()) {
                $notificationMessage = "A new volunteer event '$volunteerName' has been created! Check it out.";
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
                    showModal('success', 'You have succesfully created a volunteer event!');
                </script>";
                exit();
            } else {
                echo "Error: Could not execute the query. " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Error: Could not prepare the query. " . $conn->error;
        }
        $conn->close();

    }
}

?>
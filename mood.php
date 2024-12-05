<?php
session_start();
require_once 'connect.php';

$db = new Database();
$conn = $db->getConnect();

$budget = 0;
$remainingBudget = 0;
$totalPrice = 0;
$excessBudget = 0;

if (!isset($_SESSION['user_id'])) {
    die("User is not logged in.");
}

$user_id = $_SESSION['user_id'];

if (isset($_SESSION['budget'])) {
    $budget = $_SESSION['budget'];
}

if (isset($_POST['mood'])) {
    $mood = $_POST['mood'];
    $_SESSION['mood'] = $mood;

    $stmt = $conn->prepare("INSERT INTO `user_moods` (user_id, mood, created_at) VALUES (:user_id, :mood, NOW())");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':mood', $mood);

    if ($stmt->execute()) {
    } else {
        echo "Error saving mood.";
    }
}

$sql = "SELECT * FROM budgets";
$stmt = $conn->query($sql);

if ($stmt === false) {
    die("Error executing query: " . $conn->errorInfo()[2]);
}

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $totalPrice += $row['price'];
}

$remainingBudget = $budget - $totalPrice;

if ($remainingBudget >= 0) {
    $budgetStatus = 'Budget Met';
    $statusClass = '';
    $excessBudget = $remainingBudget; 
} else {
    $budgetStatus = 'Budget Not Met';
    $statusClass = 'budget-not-met';
    $excessBudget = 0; 
}

$mood = isset($_SESSION['mood']) ? $_SESSION['mood'] : 'Not Set';  
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mood Tracker</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="stylessss.css">
    <style>
        .receipt-container h1 {
            font-size: 24px;
            color: #000;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
            letter-spacing: 1px;
        }

        #mood-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            transition: background-color 0.3s ease;
        }

        .mood-options {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .mood-option {
            font-size: 24px;
            cursor: pointer;
            padding: 10px;
            border-radius: 50%;
            transition: background-color 0.3s;
        }

        .mood-option:hover {
            background-color: #f0f0f0;
        }

        .mood-option.selected {
            background-color: #4CAF50;
            color: white;
        }
    </style>
</head>
<body id="body">

<header>
    <a href="#" class="logo"><i class="fas fa-coins"></i> BudgetPLates</a>
    <ul class="navbar">
        <li><a href="homepage.php" class="home-active">Home</a></li>
        <li><a href="manage_income.php">Income</a></li>
        <li><a href="budget.php">Budget</a></li>
        <li><a href="report.php">Report</a></li>
        <li><a href="about.php">About</a></li>
    </ul>
</header>

<div class="receipt-container">
    <h1>Receipt</h1>
    <h2><span>Initial Budget: </span>₱<?php echo number_format($budget, 2); ?></h2>
    <h2><span>Total Price of Items: </span>₱<?php echo number_format($totalPrice, 2); ?></h2>
    <h2><span>Remaining Budget: </span>₱<?php echo number_format($remainingBudget, 2); ?></h2>

    <?php if ($excessBudget > 0): ?>
        <h2><span>Excess Budget: </span>₱<?php echo number_format($excessBudget, 2); ?></h2>
    <?php endif; ?>
    <h2 class="<?php echo $statusClass; ?>">Status: <?php echo $budgetStatus; ?></h2>

    <h2>Your Current Mood: <span><?php echo $mood; ?></span></h2>
    
    <div class="mood-options">
        <div class="mood-option <?php echo ($mood == 'Happy') ? 'selected' : ''; ?>" data-mood="Happy">
            <i class="fas fa-smile"></i>
        </div>
        <div class="mood-option <?php echo ($mood == 'Sad') ? 'selected' : ''; ?>" data-mood="Sad">
            <i class="fas fa-sad-tear"></i>
        </div>
        <div class="mood-option <?php echo ($mood == 'Neutral') ? 'selected' : ''; ?>" data-mood="Neutral">
            <i class="fas fa-meh"></i>
        </div>
        <div class="mood-option <?php echo ($mood == 'Excited') ? 'selected' : ''; ?>" data-mood="Excited">
            <i class="fas fa-grin-stars"></i>
        </div>
        <div class="mood-option <?php echo ($mood == 'Stressed') ? 'selected' : ''; ?>" data-mood="Stressed">
            <i class="fas fa-angry"></i>
        </div>
    </div>
    
    <form action="" method="POST" id="mood-form">
        <input type="hidden" name="mood" id="mood-input" value="<?php echo $mood; ?>">
        <button type="submit">Save Mood</button>
    </form>
</div>

<footer>
    <p>&copy; 2024 BudgetPlates. All rights reserved.</p>
</footer>

<div id="mood-overlay"></div>

<script>
    const moodOptions = document.querySelectorAll('.mood-option');
    const moodInput = document.getElementById('mood-input');
    const moodOverlay = document.getElementById('mood-overlay');

    function changeOverlayColor(mood) {
        switch (mood) {
            case 'Happy':
                moodOverlay.style.backgroundColor = 'rgba(255, 235, 59, 0.6)';
                break;
            case 'Sad':
                moodOverlay.style.backgroundColor = 'rgba(33, 150, 243, 0.6)'; 
                break;
            case 'Neutral':
                moodOverlay.style.backgroundColor = 'transparent'; 
                break;
            case 'Excited':
                moodOverlay.style.backgroundColor = 'rgba(255, 152, 0, 0.6)'; 
                break;
            case 'Stressed':
                moodOverlay.style.backgroundColor = 'rgba(244, 67, 54, 0.6)'; 
                break;
            default:
                moodOverlay.style.backgroundColor = 'rgba(255, 255, 255, 0.6)';
        }
    }

    moodOptions.forEach(option => {
        option.addEventListener('click', () => {
            moodOptions.forEach(opt => opt.classList.remove('selected'));
            option.classList.add('selected');
            const selectedMood = option.getAttribute('data-mood');
            moodInput.value = selectedMood;
            changeOverlayColor(selectedMood);
        });
    });

    changeOverlayColor('<?php echo $mood; ?>');
</script>

</body>
</html>

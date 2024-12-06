<?php
require_once 'Income.php';

class Notification extends Income {
    private $message;
    private $type;

    public function __construct($db) {
        parent::__construct($db);
        $this->message = '';
        $this->type = 'success';
    }

    public function setNotification($message, $type = 'success') {
        $this->message = $message;
        $this->type = $type;
    }

    public function displayNotification() {
        echo "<script>alert('{$this->message}');</script>";
    }

    public function getNotification() {
        return [
            'message' => $this->message,
            'type' => $this->type
        ];
    }

    public function createIncome($source_name, $amount, $type, $date_received, $description, $user_id) {
        $this->setSourceName($source_name);
        $this->setAmount($amount);
        $this->setType($type);
        $this->setDateReceived($date_received);
        $this->setDescription($description);
        $this->setUserId($user_id);

        $income_id = parent::create();

        if ($income_id) {
            $this->setNotification('Income data successfully saved!', 'success');
        } else {
            $this->setNotification('Failed to save income data. Please try again.', 'error');
        }

        return $income_id;
    }
}
?>

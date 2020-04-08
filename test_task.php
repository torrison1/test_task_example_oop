<?php

// Make a code for situation:
// Some man wants to travel 10 cities and have all tickets but all tickets have shuffled. How to sort tickets from start city to end city of travel ?

Class Situation {

    var $stations;

    var $tickets;
    var $tickets_original;
    var $tickets_shuffled;

    function init() {
        $this->stations = Array(
            Array('id' => '01', 'name' => 'A'),
            Array('id' => '02', 'name' => 'B'),
            Array('id' => '03', 'name' => 'C'),
            Array('id' => '04', 'name' => 'D'),
            Array('id' => '05', 'name' => 'E'),
            Array('id' => '06', 'name' => 'F'),
            Array('id' => '07', 'name' => 'G'),
            Array('id' => '08', 'name' => 'H'),
            Array('id' => '09', 'name' => 'I'),
            Array('id' => '10', 'name' => 'J'),
        );

        $this->generate_tickets();
    }

    function generate_tickets() {
        $stations = $this->stations;
        $count = count($stations);
        $tickets = Array();

        // ---------- Make Start Point ----------
        $ticket = Array();
        // Get Random Station
        $random_el = mt_rand(0, $count-1);

        $ticket['from'] = $stations[$random_el];

        unset($stations[$random_el]);
        $count--;
        $stations = array_values($stations);
        // -------------------------------------

        while ($count > 0) {

            $random_el = mt_rand(0, $count-1);
            $ticket['to'] = $stations[$random_el];
            $tickets[] = $ticket;
            $ticket = Array();
            $ticket['from'] = $stations[$random_el];

            unset($stations[$random_el]);
            $count--;
            $stations = array_values($stations);
        }

        $this->tickets_original = $tickets;

        shuffle($tickets);
        $this->tickets_shuffled = $tickets;

        return true;
    }

    function find_first_ticket() {

        $first_ticket = Array();

        // Fast Array with Key = To ID. Only First Ticket Do not Have for "From" "To" Ticket
        $fast_tickets_to = Array();
        foreach ($this->tickets_shuffled as $ticket) $fast_tickets_to[$ticket['to']['id']] = $ticket;

        foreach ($this->tickets_shuffled as $ticket) {
            if (!isset($fast_tickets_to[$ticket['from']['id']])) $first_ticket = $ticket;
        }

        return $first_ticket;
    }

    function sort_tickets() {

        $first_ticket = $this->find_first_ticket();
        // print_r($first_ticket);
        $new_tickets = Array();
        $new_tickets[] = $first_ticket;

        $tickets_shuffled = $this->tickets_shuffled;
        $fast_tickets_from_shuffled = Array();
        foreach ($this->tickets_shuffled as $ticket) $fast_tickets_from_shuffled[$ticket['from']['id']] = $ticket;

        $from_next_id = $first_ticket['to']['id'];
        while (isset($fast_tickets_from_shuffled[$from_next_id])) {
            $ticket = $fast_tickets_from_shuffled[$from_next_id];
            $from_next_id = $ticket['to']['id'];
            $new_tickets[] = $ticket;
        }

        $this->tickets = $new_tickets;
        return $new_tickets;
    }

    function show_results() {
        echo "<pre> \r\n";
        echo "============================\r\n";
        echo "Tickets Original : ".json_encode($this->tickets_original, JSON_PRETTY_PRINT)."\r\n";
        echo "Tickets Shuffled : ".json_encode($this->tickets_shuffled, JSON_PRETTY_PRINT)."\r\n";
        echo "============================\r\n";
        echo json_encode($this->tickets, JSON_PRETTY_PRINT)."\r\n";
        echo "============================\r\n";
        echo "</pre> \r\n";
    }
    function show_results_table() {
        echo "<table><tr>";
        echo "<td> <b>Tickets Original : </b> <pre>";
        echo json_encode($this->tickets_original, JSON_PRETTY_PRINT)."</pre></td>";
        echo "<td> <b>Tickets Shuffled : </b> <pre>";
        echo json_encode($this->tickets_shuffled, JSON_PRETTY_PRINT)."</pre></td>";
        echo "<td> <b>Tickets Final : </b> <pre>";
        echo json_encode($this->tickets, JSON_PRETTY_PRINT)."</pre></td>";
        echo "</tr></table>";
    }
}

$situation = new Situation();
$situation->init();
$situation->sort_tickets();
$situation->show_results_table();



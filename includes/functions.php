<?php
function init_session() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function create_form_field($name, $label, $type = 'text', $required = true) {
    $html = '<div class="form-group">';
    $html .= '<label for="' . $name . '">' . $label . '</label>';
    
    if ($type === 'textarea') {
        $html .= '<textarea id="' . $name . '" name="' . $name . '"';
        $html .= $required ? ' required' : '';
        $html .= '></textarea>';
    } else {
        $html .= '<input type="' . $type . '" id="' . $name . '" name="' . $name . '"';
        $html .= $required ? ' required' : '';
        $html .= '>';
    }
    
    $html .= '</div>';
    return $html;
}
?>

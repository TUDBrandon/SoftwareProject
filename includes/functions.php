<?php
function init_session() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function create_form_field($name, $label, $type = 'text', $required = true, $value = '') {
    $html = '<div class="form-group">';
    $html .= '<label for="' . $name . '">' . $label . '</label>';
    
    if ($type === 'textarea') {
        $html .= '<textarea name="' . $name . '" id="' . $name . '"';
        $html .= $required ? ' required' : '';
        $html .= '>' . htmlspecialchars($value) . '</textarea>';
    } else {
        $html .= '<input type="' . $type . '" name="' . $name . '" id="' . $name . '"';
        if ($type !== 'file') {
            $html .= ' value="' . htmlspecialchars($value) . '"';
        }
        $html .= $required ? ' required' : '';
        $html .= '>';
    }
    
    $html .= '</div>';
    return $html;
}
?>

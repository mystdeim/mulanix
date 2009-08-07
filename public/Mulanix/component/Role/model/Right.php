<?php

class Role_model_Right extends lib_Acl {
	
}

/*SELECT r.id 'id', a0.text 'action', a1.text 'role', a2.text 'resource', a10.text 'field', a11.text 'field', c.value 'value'
FROM `sys_right` r
LEFT JOIN `sys_alias` a0 ON r.action = a0.id
LEFT JOIN `sys_alias` a1 ON r.role = a1.id
LEFT JOIN `sys_alias` a2 ON r.resource = a2.id
LEFT JOIN `sys_criterion` c
LEFT JOIN `sys_alias` a10 ON c.field = a10.id
LEFT JOIN `sys_alias` a11 ON c.predicate = a11.id ON r.id = c.id
WHERE 1 =1*/
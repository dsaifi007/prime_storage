<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_sub_users extends CI_Migration {

    public function up() {
        $this->dbforge->add_field(array(
            'user_id' => array(
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => TRUE,
                'user_id' => 'CONSTRAINT FOREIGN KEY (id) REFERENCES table(id)'
            ),
            'controller_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '30',
            ),
        ));
//        $this->dbforge->add_column('main_admin', [
//            'CONSTRAINT fk_id FOREIGN KEY(id) REFERENCES table(id)',
//        ]);
        //$this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('sub_admin');
    }

    public function down() {
        $this->dbforge->drop_table('sub_admin');
    }

}

?>
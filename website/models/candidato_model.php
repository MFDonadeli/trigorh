<?php
    class Candidato_model extends CI_Model
    {
        private function check_password($password, $pass_db, $salt)
        {
            $hashed = sha1($password . $this->password_salt);
            return ($hashed === $pass_db);    
        }

        private function gerar_senha($senha)
        {
            $salt = openssl_random_pseudo_bytes(16);
            $senha = sha1($senha . $salt);

            return array($salt, $senha);
        }

        public function login_user($email,$senha)
        {   
            //Validate
            $this->db->where('email',$email);
            
            $result = $this->db->get('usuario');
            if($result->num_rows() == 1)
            {
                if(!check_password($senha, $result->row(0)->senha, $result->row(0)->salt))
                    return false;
    
                return $result->row(0)->id;
            } 
            else
            {
                return false;
            }
        }

        public function has_user($email)
        {
            $this->db->where('email',$email);
            $result = $this->db->get('usuario');
            
            if($result->num_rows() == 1)
            {
                if($result->row(0)->idprofissional != "-1")
                {
                    return 1; //Já tem profissional
                }
                else if($result->row(0)->idempresa != "-1")
                {
                    return 2; //Já tem empresa
                }
            }
            else
                return 0;
        }


        public function insere_candidato($nome, $email, $senha, $cpf, $empresa = false)
        {
            $novo_profissional = array(
                'email' => $email,
                'ativo' => '1',
                'nome' => $nome,
                'cpf' => preg_replace('/[^0-9]/', '', (string) $cpf)
            );

            $this->db->insert('profissional', $novo_profissional);

            $insert_id = $this->db->insert_id();

            $pass_pair = $this->gerar_senha($senha);

            if($empresa)
            {
                $this->db->set('idprofissional', $insert_id);
                $this->db->where('idempresa', $empresa);
                $this->db->update('usuario');
            }
            else
            {
                $novo_usuario = array(
                    'idprofissional' => $insert_id,
                    'idempresa' => '-1',
                    'email' => $email,
                    'senha' => $pass_pair[1],
                    'salt' => $pass_pair[0]
                );

                $this->db->insert('usuario', $novo_usuario);

                return $insert_id;
            }
            
            return false;
        }

    }
?>
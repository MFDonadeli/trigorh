<?php
    class Empresa_model extends CI_Model
    {
        public function __construct()
        {
            parent::__construct();
            $this->load->helper('password_helper');
        }

        /**
        * login_user
        *
        * Verifica se o par usuario e senha são existentes
        *
        * @param	string
        * @param	string 
        * @return	boolean
        */
        public function login_user($email,$senha)
        {   
            log_message('debug', 'login_user. Param1: {' . $email . '} Param2: {' . $senha . '}');

            //Validate
            $this->db->where('email',$email);
            $this->db->where('idempresa != -1');
            
            $result = $this->db->get('usuario');

            log_message('debug', 'Last Query: ' . $this->db->last_query());

            if($result->num_rows() == 1)
            {
                if(!check_password($senha, $result->row(0)->senha, $result->row(0)->salt))
                    return false;
    
                return $result->row(0)->idempresa;
            } 
            else
            {
                return false;
            }
        }

        /**
        * informacao_basica
        *
        * Traz informações básicas da empresa
        *
        * @param	string
        * @return	int O tipo do profissional ou 0 se o profissional não existir
        */
        public function informacao_basica($id)
        {
            
        }

        /**
        * get_vagas
        *
        * Traz vagas anunciadas pela empresa
        *
        * @param	string
        * @return	int O tipo do profissional ou 0 se o profissional não existir
        */
        public function get_vagas($id)
        {
            log_message('debug', 'get_vagas. Param1: {' . $id);

            $this->db->where('idempresa',$id);
            $result = $this->db->get('vaga');
            
            return $result->result();
        }

        /**
        * has_user
        *
        * Verifica se existe ou não a empresa e seu tipo
        *
        * @param	string
        * @param	string 
        * @return	int O tipo do profissional ou 0 se o profissional não existir
        */
        public function has_user($email, $cnpj)
        {
            log_message('debug', 'has_user. Param1: {' . $email . '} Param2: {' . $cnpj . '}');

            $this->db->where('email',$email);
            $result = $this->db->get('usuario');

            $this->db->where('cnpj', preg_replace('/[^0-9]/', '', (string) $cnpj));
            $result_prof = $this->db->get('empresa');
            
            if($result->num_rows() > 0 || $result_prof->num_rows() > 0)
            {
                if($result->num_rows() > 0)
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

                return 3; //Já tem empresa, neste cnpj
            }
            else
                return 0; //Não tem empresa
        }

        /**
        * get_user
        *
        * Traz id do profissional se existir na tabela
        *
        * @param	string
        * @param	string 
        * @return	mixed O id do profissional ou false se o profissional não existir
        */
        public function get_user($email, $cpf)
        {
            log_message('debug', 'get_user. Param1: {' . $email . '} Param2: {' . $cpf . '}');

            $this->db->where('email',$email);
            $result = $this->db->get('usuario');

            $this->db->where('cpf', preg_replace('/[^0-9]/', '', (string) $cpf));
            $result_prof = $this->db->get('profissional');
            
            if($result->num_rows() > 0 || $result_prof->num_rows() > 0)
            {
                return $result->row(0)->idprofissional;
            }
            else
                return false;
        }

        /**
        * insere_empresa
        *
        * Insere nova empresa no Banco de Dados, nas tabelas empresa e usuario
        *
        * @param	string
        * @param	string
        * @param	string
        * @param	string
        * @param	boolean
        * @return	mixed O id do profissional ou false se ocorreu algum erro
        */
        public function insere_empresa($nova_empresa, $profissional = false)
        {
            log_message('debug', 'insere_empresa. Param1: {' . $nome . '} Param2: {' . $email . '} Param3: {' . $senha . '} Param4: {' . $cnpj . '} Param5: {' . $profissional . '}');

            $senha = $nova_empresa['senha'];
            unset($nova_empresa['senha']);
            
            $this->db->insert('empresa', $nova_empresa);

            $insert_id = $this->db->insert_id();

            $this->registra_acao($insert_id, Acao::CADASTRO_EMPRESA);

            $pass_pair = gerar_senha($senha);

            if($empresa)
            {
                $this->db->set('idempresa', $insert_id);
                $this->db->where('idprofissional', $profissional);
                $this->db->update('usuario');
            }
            else
            {
                $novo_usuario = array(
                    'idprofissional' => '-1',
                    'idempresa' => $insert_id,
                    'email' => $email,
                    'senha' => $pass_pair[1],
                    'salt' => $pass_pair[0]
                );

                $this->db->insert('usuario', $novo_usuario);

                return $insert_id;
            }
            
            return false;
        }

        /**
        * registra_acao
        *
        * Grava ação do profissional no site
        *
        * @param	int
        * @param	int (constante da classe Acao)
        * @return	string O hash da acao gravada no banco
        */
        public function registra_acao($id, $acao)
        {
            log_message('debug', 'registra_acao. Param1: {' . $id . '} Param2: {' . $acao . '}');

            $hash = sha1(date('Y-m-d H:i:s') . $id . $acao);

            $nova_acao = array(
                'idempresa' => $id,
                'idacao' => $acao,
                'hash' => $hash
            );

            $this->db->insert('empresa_historico', $nova_acao);
            
            return $hash;
        }
    }
?>
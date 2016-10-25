<?php
    class Candidato_model extends CI_Model
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
            
            $result = $this->db->get('usuario');
            if($result->num_rows() == 1)
            {
                if(!check_password($senha, $result->row(0)->senha, $result->row(0)->salt))
                    return false;
    
                return $result->row(0)->idusuario;
            } 
            else
            {
                return false;
            }
        }

        /**
        * has_user
        *
        * Verifica se existe ou não o profissional e seu tipo
        *
        * @param	string
        * @param	string 
        * @return	int O tipo do profissional ou 0 se o profissional não existir
        */
        public function has_user($email, $cpf)
        {
            log_message('debug', 'has_user. Param1: {' . $email . '} Param2: {' . $cpf . '}');

            $this->db->where('email',$email);
            $result = $this->db->get('usuario');

            $this->db->where('cpf', preg_replace('/[^0-9]/', '', (string) $cpf));
            $result_prof = $this->db->get('profissional');
            
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

                return 3; //Já tem profissional, neste cpf
            }
            else
                return 0; //Não tem profissional
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
        * insere_candidato
        *
        * Insere novo candidato no Banco de Dados, nas tabelas profissional e usuario
        *
        * @param	string
        * @param	string
        * @param	string
        * @param	string
        * @param	boolean
        * @return	mixed O id do profissional ou false se ocorreu algum erro
        */
        public function insere_candidato($nome, $email, $senha, $cpf, $empresa = false)
        {
            log_message('debug', 'insere_candidato. Param1: {' . $nome . '} Param2: {' . $email . '} Param3: {' . $senha . '} Param4: {' . $cpf . '} Param5: {' . $empresa . '}');

            $novo_profissional = array(
                'email' => $email,
                'ativo' => '1',
                'nome' => $nome,
                'cpf' => preg_replace('/[^0-9]/', '', (string) $cpf)
            );

            $this->db->insert('profissional', $novo_profissional);

            $insert_id = $this->db->insert_id();

            $this->registra_acao($insert_id, Acao::CADASTRO_CV);

            $pass_pair = gerar_senha($senha);

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

        /**
        * atualiza_senha
        *
        * Atualiza senha do profissional no banco de dados
        *
        * @param	int
        * @param	string 
        * @return	boolean
        */
        public function atualiza_senha($id, $senha)
        {
            log_message('debug', 'atualiza_senha. Param1: {' . $id . '} Param2: {' . $senha . '}');

            $pass_pair = gerar_senha($senha);

            log_message('debug', 'senhas novas Senha: ' . $pass_pair[0] . ' Salt: ' . $pass_pair[1]);

            $this->db->set('senha', $pass_pair[0]);
            $this->db->set('salt', $pass_pair[1]);

            $this->db->where('idprofissional', $id);
            $this->db->update('usuario');

            log_message('debug', 'Last Query: ' . $this->db->last_query());

            $this->registra_acao($id, Acao::MUDOU_SENHA);
            
            return true;
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
                'idprofissional' => $id,
                'idacao' => $acao,
                'hash' => $hash
            );

            $this->db->insert('profissional_historico', $nova_acao);
            
            return $hash;
        }

        /**
        * check_pedido_senha
        *
        * Verifica se o pedido de alterar senha foi mesmo feito
        * Verificando se existe id do profissional que quer alterar senha, juntamente com um hash
        *
        * @param	id
        * @param	string
        * @return	boolean	true ou false se existir o pedido
        */
        public function check_pedido_senha($id, $hash)
        {
            log_message('debug', 'check_pedido_senha. Param1: {' . $id . '} Param2: {' . $hash . '}');

            $this->db->where('idprofissional',$id);
            $this->db->where('hash',$hash);
            $result = $this->db->get('profissional_historico');

            if($result->num_rows() > 0)
            {
                return true;
            }
            else
                return false;    
        }

    }
?>
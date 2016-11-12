<?php
    class Vaga_model extends CI_Model
    {
        public function __construct()
        {
            parent::__construct();
            $this->load->helper('password_helper');
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
        * insere_vaga
        *
        * Insere vaga
        *
        * @param	string
        * @param	string
        * @return	boolean	true ou false se existir o pedido
        */
        public function insere_vaga($vaga, $id)
        {
            log_message('debug', 'insere_vaga. Param1: ' . $vaga . " Param2: " . $id);

            $arr = array(
                'codvaga' => $vaga,
                'idempresa' => $id
            );

            $this->db->insert('vaga', $arr);  

            log_message('debug', 'Last Query: ' . $this->db->last_query()); 

            return $this->db->insert_id(); 
        }

        /**
        * apaga_vaga
        *
        * Apaga vaga
        *
        * @param	string
        * @return	boolean	true ou false se existir o pedido
        */
        public function apaga_vaga($vaga)
        {
            log_message('debug', 'apaga_vaga. Param1: ' . $vaga);

            $this->db->where('idvaga', $vaga);
            $result = $this->db->get('profissional_vaga');

            if($result->num_rows() > 0)
            {
                return false;
            }

            $this->db->where('idvaga', $vaga);

            $this->db->delete('vaga');  

            log_message('debug', 'Last Query: ' . $this->db->last_query());
        }

        /**
        * update_dados_vaga
        *
        * Atualiza dados pessoais de profissional
        *
        * @param	array
        * @param	array
        * @return	boolean	true ou false se existir o pedido
        */
        public function update_dados_vaga($arr, $beneficio)
        {
            log_message('debug', 'update_dados_vaga');

            $this->db->where('idvaga', $arr['idvaga']);
            $this->db->update('vaga', $arr);

            if(is_array($beneficio))
            {
                $this->db->where('idvaga', $arr['idvaga']);
                $this->db->delete('beneficio_vaga');

                foreach($beneficio as $benef)
                {
                    $arrbenef = array(
                        'idvaga' => $arr['idvaga'],
                        'idbeneficio' => $benef
                    );
                    $this->db->insert('beneficio_vaga', $arrbenef);
                }
            }  
            else
                log_message('debug', 'Benefício não é array');    

            log_message('debug', 'Last Query: ' . $this->db->last_query());
        }

        /**
        * get_dados_vaga
        *
        * Busca dados pessoais de vaga
        *
        * @param	int
        * @return	array
        */
        public function get_dados_vaga($id)
        {
            log_message('debug', 'get_dados_vaga. Param1: ' . $id);

            $this->db->where('idvaga', $id);
            $result = $this->db->get('vaga');

            log_message('debug', 'Last Query: ' . $this->db->last_query());

            return $result->row();
        }

        /**
        * get_beneficios_vaga
        *
        * Busca dados pessoais de vaga
        *
        * @param	int
        * @return	array
        */
        public function get_beneficios_vaga($id)
        {
            log_message('debug', 'get_beneficios_vaga. Param1: ' . $id);

            $this->db->where('idvaga', $id);
            $result = $this->db->get('beneficio_vaga');

            log_message('debug', 'Last Query: ' . $this->db->last_query());

            return $result->result();
        }

        /**
        * update_idioma
        *
        * Insere/Atualiza dados de formação acadêmica da vaga
        *
        * @param	array
        * @return	boolean	true ou false se existir o pedido
        */
        public function update_idioma($arr, $ididioma)
        {
            log_message('debug', 'update_idioma. Idioma: ' . $ididioma);

            if(empty($ididioma))
            {
                $this->db->insert('conhecimento_vaga', $arr);
            }
            else
            {
                $this->db->where('idconhecimento_vaga', $ididioma);
                $this->db->update('conhecimento_vaga', $arr);    
            }

            log_message('debug', 'Last Query: ' . $this->db->last_query());
        }

        /**
        * update_idioma
        *
        * Insere/Atualiza dados de formação acadêmica do profissional
        *
        * @param	array
        * @return	boolean	true ou false se existir o pedido
        */
        public function update_informatica($arr, $idinformatica)
        {
            log_message('debug', 'update_idioma');

            if(empty($idinformatica))
            {
                $this->db->insert('conhecimento_vaga', $arr);
            }
            else
            {
                $this->db->where('idconhecimento_vaga', $idinformatica);
                $this->db->update('conhecimento_vaga', $arr);    
            }

            log_message('debug', 'Last Query: ' . $this->db->last_query());

        }

        /**
        * update_curso
        *
        * Insere/Atualiza dados de cursos da vaga
        *
        * @param	array
        * @return	boolean	true ou false se existir o pedido
        */
        public function update_curso($arr, $idcurso)
        {
            log_message('debug', 'update_curso. Param1: ' . $idcurso);

            if(empty($idcurso))
            {
                $this->db->insert('formacao_vaga', $arr);
            }
            else
            {
                $this->db->where('idformacao_vaga', $idcurso);
                $this->db->update('formacao_vaga', $arr);    
            }

            log_message('debug', 'Last Query: ' . $this->db->last_query());
        }

        
        /**
        * get_idioma
        *
        * Seleciona um idioma da vaga
        *
        * @param	int
        * @param	int
        * @return	boolean	true ou false se existir o pedido
        */
        public function get_idioma($id, $id_idioma)
        {
            log_message('debug', 'get_idioma. Param1: ' . $id . ' Param2: ' . $id_idioma);

            $this->db->select('conhecimento_vaga.idconhecimento_vaga, conhecimento_vaga.idconhecimento,
                                conhecimento_vaga.id_vaga, conhecimento_vaga.conhecimento, conhecimento_vaga.obrigatorio, 
                                conhecimento_vaga.nivel, nivel_idioma.nivel_idioma');
            $this->db->from('conhecimento_vaga');
            $this->db->join('nivel_idioma', 
                'conhecimento_vaga.nivel = nivel_idioma.idnivel_idioma');
            $this->db->where('conhecimento_vaga.id_vaga', $id);
            $this->db->where('conhecimento_vaga.idconhecimento_vaga', $id_idioma);
            $result = $this->db->get();

            log_message('debug', 'Last Query: ' . $this->db->last_query());

            return $result->row();
        }

        /**
        * get_idiomas
        *
        * Busca os idiomas do profissional
        *
        * @param	int
        * @return	array
        */
        public function get_idiomas($id)
        {
            log_message('debug', 'get_idiomas. Param1: ' . $id);

            $this->db->select('conhecimento_vaga.*, nivel_idioma.nivel_idioma');
            $this->db->from('conhecimento_vaga');
            $this->db->join('conhecimento', 
                'conhecimento_vaga.idconhecimento = conhecimento.idconhecimento');
            $this->db->join('nivel_idioma', 
                'conhecimento_vaga.nivel = nivel_idioma.idnivel_idioma');
            $this->db->where('conhecimento.idtipo_conhecimento = 2');
            $this->db->where('conhecimento_vaga.id_vaga', $id);
            $result = $this->db->get();

            log_message('debug', 'Last Query: ' . $this->db->last_query());

            return $result->result();    
        }

        /**
        * apaga_idioma
        *
        * Apaga dados de idioma do profissional
        *
        * @param	int
        * @param	int
        * @return	boolean	true ou false se existir o pedido
        */
        public function apaga_idioma($id, $id_idioma)
        {
            log_message('debug', 'apaga_idioma');

            $this->db->where('idconhecimento_vaga', $id_idioma);
            $this->db->where('id_vaga', $id);
            $this->db->delete('conhecimento_vaga');

            log_message('debug', 'Last Query: ' . $this->db->last_query());
        }

//****** INFORMATICA

        /**
        * get_informatica
        *
        * Seleciona um conhecimento de informática da vaga
        *
        * @param	int
        * @param	int
        * @return	boolean	true ou false se existir o pedido
        */
        public function get_informatica($id, $id_informatica)
        {
            log_message('debug', 'get_informatica. Param1: ' . $id . ' Param2: ' . $id_informatica);

            $this->db->where('conhecimento_vaga.id_vaga', $id);
            $this->db->where('conhecimento_vaga.idconhecimento_vaga', $id_informatica);
            $result = $this->db->get('conhecimento_vaga');

            log_message('debug', 'Last Query: ' . $this->db->last_query());

            return $result->row();
        }

        /**
        * get_idiomas
        *
        * Busca os conhecimento de informática da vaga
        *
        * @param	int
        * @return	array
        */
        public function get_informaticas($id)
        {
            log_message('debug', 'get_idiomas. Param1: ' . $id);

            $this->db->select('conhecimento_vaga.*, subtipo_conhecimento.subtipo_conhecimento');
            $this->db->from('conhecimento_vaga');
            $this->db->join('conhecimento', 
                'conhecimento_vaga.idconhecimento = conhecimento.idconhecimento');
            $this->db->join('subtipo_conhecimento', 
                'conhecimento.idsubtipo_conhecimento = subtipo_conhecimento.idsubtipo_conhecimento');
            $this->db->where('conhecimento.idtipo_conhecimento = 1');
            $this->db->where('conhecimento_vaga.id_vaga', $id);
            $this->db->order_by('subtipo_conhecimento.subtipo_conhecimento');
            $result = $this->db->get();

            log_message('debug', 'Last Query: ' . $this->db->last_query());

            return $result->result();    
        }

        /**
        * apaga_informática
        *
        * Apaga dados de informática do profissional
        *
        * @param	int
        * @param	int
        * @return	boolean	true ou false se existir o pedido
        */
        public function apaga_informatica($id, $id_informatica)
        {
            log_message('debug', 'apaga_informática');

            $this->db->where('idconhecimento_vaga', $id_informatica);
            $this->db->where('id_vaga', $id);
            $this->db->delete('conhecimento_vaga');

            log_message('debug', 'Last Query: ' . $this->db->last_query());
        }

/*** CURSOS
    /**
        * get_curso
        *
        * Seleciona um curso requerido da vaga
        *
        * @param	int
        * @param	int
        * @return	boolean	true ou false se existir o pedido
        */
        public function get_curso($id, $idcurso)
        {
            log_message('debug', 'get_curso. Param1: ' . $id . ' Param2: ' . $idcurso);

            $this->db->where('idvaga', $id);
            $this->db->where('idformacao_vaga', $idcurso);
            $result = $this->db->get('formacao_vaga');

            log_message('debug', 'Last Query: ' . $this->db->last_query());

            return $result->row();
        }

        /**
        * get_cursos
        *
        * Busca as formações necessárias para a vaga
        *
        * @param	int
        * @return	array
        */
        public function get_cursos($id)
        {
            log_message('debug', 'get_cursos. Param1: ' . $id);

            $this->db->where('idvaga', $id);
            $result = $this->db->get('formacao_vaga');

            log_message('debug', 'Last Query: ' . $this->db->last_query());

            return $result->result();    
        }

        /**
        * apaga_cursos
        *
        * Apaga curso requerido para a vaga
        *
        * @param	int
        * @param	int
        * @return	boolean	true ou false se existir o pedido
        */
        public function apaga_cursos($id, $idcurso)
        {
            log_message('debug', 'apaga_idioma');

            $this->db->where('idvaga', $id);
            $this->db->where('idformacao_vaga', $idcurso);
            $this->db->delete('formacao_vaga');

            log_message('debug', 'Last Query: ' . $this->db->last_query());
        }

        /**
        * informacao_basica
        *
        * Traz nome, porcentagem do currículo preenchido e data da última atualização do currículo
        *
        * @param	int
        * @return	array
        */
        public function informacao_basica($id)
        {
            log_message('debug', 'informacao_basica. Param1: ' . $id);

            $this->db->select('p.nome, p.porcentagem_cadastro, ph.data');
            $this->db->from('profissional p');
            $this->db->join('profissional_historico ph', 'p.idprofissional = ph.idprofissional');
            $this->db->where('p.idprofissional', $id);
            $this->db->where('ph.idacao = 2');
            $this->db->order_by('ph.idprofissional_historico', 'DESC');
            $this->db->limit(1);
            $result = $this->db->get();

            return $result->row();
        }

        public function busca_informatica($conhecimento)
        {
            log_message('debug', 'busca_idioma. Param1: ' . $conhecimento);

            $this->db->select('conhecimento.idconhecimento, conhecimento.conhecimento');
            $this->db->from('conhecimento');
            $this->db->like('conhecimento.conhecimento', $conhecimento, 'both');
            $this->db->where('idtipo_conhecimento', '1');
            $this->db->order_by('conhecimento.conhecimento');
            
            $result = $this->db->get();

            log_message('debug', 'Last Query: ' . $this->db->last_query());

            return $result->result();
        }

        public function busca_idioma($conhecimento)
        {
            log_message('debug', 'busca_idioma. Param1: ' . $conhecimento);

            $this->db->select('conhecimento.idconhecimento, conhecimento.conhecimento');
            $this->db->from('conhecimento');
            $this->db->like('conhecimento.conhecimento', $conhecimento, 'both');
            $this->db->where('idtipo_conhecimento', '2');
            $this->db->order_by('conhecimento.conhecimento');
            
            $result = $this->db->get();

            log_message('debug', 'Last Query: ' . $this->db->last_query());

            return $result->result();
        }

    }
?>
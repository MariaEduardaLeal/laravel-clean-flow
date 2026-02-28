# 🏗️ Gráfica JB - Padrão Arquitetural Laravel

Biblioteca oficial da equipe de Engenharia da **Gráfica JB**. Desenvolvida para padronizar, acelerar e garantir a qualidade da arquitetura em nossos projetos Laravel.

Esta ferramenta automatiza a criação de arquivos baseados no padrão **Controller → Service → Repository**, garantindo a aplicação de Clean Code, princípios SOLID e Injeção de Dependência logo no momento da geração do código.

---

## 🎯 Nossa Filosofia (Regras de Ouro)

Para manter nossos sistemas escaláveis e fáceis de dar manutenção, este pacote força as seguintes diretrizes:

1. **Controllers Enxutos:** O Controller serve **apenas** para receber a requisição HTTP e devolver a resposta. Nunca escreva regras de negócio aqui.
2. **Services (Regras de Negócio):** Toda lógica, cálculos, envio de e-mails e processos complexos moram na camada de Service.
3. **Repositories (Banco de Dados):** O acesso aos dados fica estritamente isolado nos Repositories. O Service pede os dados para o Repository.
4. **Injeção de Dependência:** As classes geradas interagem através de injeção no construtor, facilitando a criação de *Mocks* e testes unitários. Não utilizamos métodos estáticos para regras de negócio.
5. **Padrão de Nomenclatura:** Todos os métodos, funções e variáveis geradas utilizam o padrão `snake_case`.
6. **Documentação Rigorosa:** Todos os métodos gerados já incluem DocBlocks detalhados (`@param`, `@return`, `@throws`).

---

## ⚙️ Instalação

Como o pacote está publicado no Packagist, a instalação em qualquer projeto novo é feita com um único comando:

```bash
composer require graficajb/arquitetura-padrao
```

> **Nota para ambientes conteinerizados:** Se o seu projeto estiver rodando com Docker (por exemplo, via Laravel Sail), lembre-se de rodar `./vendor/bin/sail composer require graficajb/arquitetura-padrao`.

A biblioteca utiliza o recurso de **Auto-Discovery** do Laravel, portanto, nenhum Service Provider precisa ser registrado manualmente. Instalou, está pronto para usar!

---

## 📦 Comandos Disponíveis

### 1. O Comando Mestre: `make:flow` ⭐ Recomendado

Gera o ecossistema completo de uma nova funcionalidade de uma só vez, interligando todas as camadas corretamente.

```bash
php artisan make:flow Agendamento
```

**O que ele cria:**

| Arquivo | Caminho |
|---|---|
| Model | `app/Models/Agendamento.php` |
| Repository | `app/Repositories/AgendamentoRepository.php` |
| Service | `app/Services/AgendamentoService.php` |
| Controller | `app/Http/Controllers/AgendamentoController.php` |

- O **Repository** já vem com listagem paginada em `snake_case` plural: `get_paginated_agendamentos`
- O **Service** já vem com o método principal `process_agendamento` e injeção do Repository
- O **Controller** já vem com o Service injetado no construtor de forma limpa

---

### 2. Criando Apenas um Service: `make:service`

Gera uma camada de serviço isolada. A biblioteca adiciona o sufixo `Service` automaticamente caso você esqueça.

```bash
php artisan make:service RelatorioFinanceiro
```

**O que ele gera:** Um arquivo `RelatorioFinanceiroService.php` contendo:

- Estrutura base orientada a objetos (sem métodos estáticos)
- Método principal nomeado dinamicamente: `process_relatorio_financeiro`
- DocBlocks com lembretes arquiteturais para uso de Transactions (`DB`) em operações críticas e Queues/Jobs para envios demorados

---

### 3. Criando Apenas um Repository: `make:repository`

Gera uma classe isolada focada na camada de persistência.

```bash
php artisan make:repository Cliente
```

**O que ele gera:** Um arquivo `ClienteRepository.php` contendo:

- Importação automática do Model `Cliente`
- Método estruturado para paginação
- DocBlocks educativos alertando a equipe sobre o uso de **Cache**, **Indexação** nas tabelas e o perigo de não utilizar Paginação em tabelas extensas

---

## 💡 Boas Práticas Pós-Geração

O código gerado é um molde perfeito, mas a responsabilidade de mantê-lo limpo é sua. Siga estas dicas ao preencher a lógica:

**Respostas da API**
No Controller, ao devolver a resposta do Service, planeje sempre o uso de API Resources (`php artisan make:resource`) para padronizar o retorno JSON e pensar no versionamento da API.

**Transações**
No Service, se for salvar múltiplos registros dependentes (ex: criar um usuário e gerar a fatura dele logo em seguida), descomente o bloco `DB::beginTransaction()` e `DB::commit()` fornecido no stub.

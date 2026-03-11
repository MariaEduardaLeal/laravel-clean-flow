# Guia de Contribuição

Obrigado por considerar contribuir para o pacote **Laravel Clean Flow**! Este documento descreve as etapas e diretrizes para quem deseja submeter melhorias, correções de bugs ou novas funcionalidades.

## Como Contribuir

1. **Faça um Fork do Repositório**
   Faça um fork do repositório para a sua própria conta do GitHub.

2. **Clone o seu Fork**

   ```bash
   git clone https://github.com/MariaEduardaLeal/laravel-clean-flow
   cd laravel-clean-flow
   ```

3. **Crie uma Branch de Funcionalidade**

   ```bash
   git checkout -b minha-nova-funcionalidade
   ```

4. **Instale as Dependências**
   Esta biblioteca utiliza o [Pest](https://pestphp.com/) para os testes automatizados.

   ```bash
   composer install
   ```

5. **Desenvolva e Teste**
   Faça suas modificações. Antes de commitar, por favor, certifique-se de que os testes continuam passando e crie novos testes para cobrir seu código:

   ```bash
   ./vendor/bin/pest
   ```

6. **Faça o Commit das suas Modificações**
   Escreva mensagens de commit claras e curtas explicando o **porquê** da alteração.

   ```bash
   git commit -m "feat: Adiciona nova stub para geração de Testes"
   ```

7. **Envie (Push) para a sua Branch**

   ```bash
   git push origin minha-nova-funcionalidade
   ```

8. **Abra um Pull Request**
   Vá até a página do repositório original no GitHub e abra um Pull Request. Explique detalhadamente quais as mudanças realizadas, o contexto do porquê foram feitas e as validações testadas.

## Relatando Bugs

Se você encontrar um bug, fique à vontade para abrir uma Issue detalhando o problema, os passos para reproduzir, e as versões relevantes instaladas (PHP, Laravel, sistema).

## Sugerindo Melhorias

Sugestões também são muito bem-vindas na aba de Issues. Use um título claro, descreva a sugestão em detalhes e indique que formato de uso seria benefício para os desenvolvedores.

Agradecemos o apoio na evolução da ferramenta! Caso prefira conversar antes de codificar, sinta-se livre para entrar em contato com os mantenedores ou deixar uma Issue para discussão.

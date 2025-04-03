# Auto SAML Cohort

## 🔍 Resumo
Plugin para Moodle que automatiza a atribuição de usuários autenticados via SAML2 a uma coorte específica, simplificando o gerenciamento de inscrições em ambientes educacionais com autenticação federada.

## ✨ Principais características
- **Adição automática** de usuários SAML2 à coorte designada durante criação e login
- Interface de administração integrada para **monitoramento e gestão**
- Recurso de **sincronização** para usuários existentes com um único clique
- Compatível com Moodle **4.1+**
- Suporta múltiplos idiomas (**PT-BR** e **EN**)
- Simplicidade na configuração e uso

## 🔧 Tecnicamente
> Este plugin funciona através de observadores de eventos que monitoram a criação de usuários e logins, verificando se a autenticação é via SAML2. A integração é não-invasiva e respeita a arquitetura do Moodle, utilizando as bibliotecas de coorte nativas.

## 💼 Caso de uso
Ideal para instituições educacionais que utilizam federação SAML (como *CAFe* no Brasil) e precisam automatizar o processo de inscrição em cursos através do mecanismo de coortes do Moodle.

## 📋 Requisitos
```
- Moodle 4.1 ou superior
- Plugin de autenticação SAML2 habilitado
- Pelo menos uma coorte criada
```

## 🚀 Instalação
1. Instale o plugin através da interface de instalação de plugins do Moodle
2. Vá para `Administração do site > Plugins > Plugins locais > Auto SAML Cohort`
3. Selecione a coorte onde os usuários devem ser adicionados
4. Configure a inscrição por coorte nos cursos desejados

## 🔒 Licença
***GNU General Public License v3*** - Software livre para uso, modificação e distribuição conforme os termos da licença.

---

**Desenvolvido por Victor Fioravante** | Versão atual: `1.3`

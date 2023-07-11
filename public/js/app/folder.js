(() => {
  const createModalForm = document.querySelector('[name="createModalForm"]');
  const editModalForm = document.querySelector('[name="createModalForm"]');

  const handleUpdateFolder = (event) => {
    event.preventDefault();
  };

  const handleCreateFolder = async (event) => {
    event.preventDefault();
    const form = event.target;
    const url = API_CREATE_FOLDER;
    const method = form.getAttribute("method");
    const data = { ...getValues(form) };

    const res = await fetch(url, {
      body: JSON.stringify(data),
      method,
    });

    const responseData = await res.json();

    if (!res.ok) {
      if (res.status === 400) {
        console.error(res);
        return validate(responseData?.violations ?? {}, form);
      }
      return errorHTTPRequest();
    }
    if (res.ok && res.status === 201) {
      resetValidation(form);
      form.reset();
      const folder = {
        ...responseData,
        routes: {
          delete: res.headers.get("Delete-Route"),
          edit: res.headers.get("Edit-Route"),
          show: res.headers.get("Show-Route"),
          apiShow: res.headers.get("Api-Show-Route"),
        },
      };
      addFolderRow(folder);
      return new AFToast("Le dossier a bien été enregistré 👍", "info", {
        duration: 3000,
        isDismissible: true,
      });
    }

    return console.warn({ data, method, url, form, res });
  };

  const addFolderRow = (data = {}) => {
    const date = new Date(data?.updatedAt ?? data.createdAt);
    const container = document.getElementById("folderList");
    const template = document.getElementById("folderRowTemplate");
    const clone = template.content.cloneNode(true);
    clone.querySelector(
      "[data-folder-date]"
    ).innerHTML = `${date.toLocaleDateString(
      "fr"
    )} ${date.toLocaleTimeString()}`;
    clone.querySelector("[data-folder-name]").innerHTML = `${data?.name}`;
    clone.querySelector("[data-folder-name]").href = `${data?.routes?.show}`;
    clone.querySelector("[data-folder-id]").innerHTML = `${data?.id}`;
    clone.querySelector("[data-folder-rename]").href = `${data?.routes?.edit}`;
    clone.querySelector(
      "[data-folder-archieved]"
    ).href = `${data?.routes?.edit}`;
    clone.querySelector(
      "[data-folder-delete]"
    ).href = `${data?.routes?.delete}`;
    clone
      .querySelector("[data-folder-properties]")
      .setAttribute("data-href", data?.routes?.apiShow);

    return container.querySelector("tbody").appendChild(clone);
  };

  createModalForm &&
    createModalForm.addEventListener("submit", handleCreateFolder);

  editModalForm && editModalForm.addEventListener("submit", handleUpdateFolder);
})();

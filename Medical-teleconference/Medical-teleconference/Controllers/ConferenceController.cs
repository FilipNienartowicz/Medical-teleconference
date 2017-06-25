using Medical_teleconference.Models;
using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Web;
using System.Web.Mvc;

namespace Medical_teleconference.Controllers
{
    public class ConferenceController : Controller
    {
        private TeleconferenceDbContext db = new TeleconferenceDbContext();

        //
        // GET: /Conference/

        public ActionResult Index(int id)
        {
            Room room = db.Rooms.Find(id);
            ViewBag.RoomName = room.RoomName;
            ViewBag.RoomId = id;
            
            List<Photo> photosInRoom = new List<Photo>();

            foreach (Photo p in db.Photos)
            {
                if(p.RoomId == id)
                {
                    photosInRoom.Add(p);
                }
            }

            return View(photosInRoom);
        }

        public ActionResult Show(int? id)
        {
            Photo p = db.Photos.Find(id);
            string b64 = Convert.ToBase64String(p.photo);
            string content = "data:" + p.MimeType + ";base64," + b64;

            return Content(content);
        }

        //[HttpPost]
        public ActionResult Upload(HttpPostedFileBase image, string RoomId)
        {
            string pic = System.IO.Path.GetFileName(image.FileName);
            string type = pic.Split('.').Last();
            string mime = "image/" + type;

            if (image != null)
            {
                using (MemoryStream ms = new MemoryStream())
                {
                    image.InputStream.CopyTo(ms);
                    byte[] array = ms.GetBuffer();

                    int i = db.Photos.Count();
                    Photo photo = new Photo();
                    photo.PhotoId = i;
                    photo.RoomId = int.Parse(RoomId);
                    photo.photo = array;
                    photo.MimeType = mime;

                    db.Photos.Add(photo);
                    db.SaveChanges();
                }

            }

            return RedirectToAction("Index", new { id = int.Parse(RoomId)});
        }

    }
}

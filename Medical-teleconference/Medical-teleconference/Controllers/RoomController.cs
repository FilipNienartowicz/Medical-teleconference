using System;
using System.Collections.Generic;
using System.Data;
using System.Data.Entity;
using System.Linq;
using System.Web;
using System.Web.Mvc;
using Medical_teleconference.Models;
using WebMatrix.WebData;
using Medical_teleconference.Filters;

namespace Medical_teleconference.Controllers
{
    public class RoomController : Controller
    {
        private TeleconferenceDbContext db;

        public RoomController()
        {
            db = new TeleconferenceDbContext();
        }

        //
        // GET: /Room/
        [InitializeSimpleMembership]
        public ActionResult Index()
        {
            if (Models.User.IsLoggedIn())
            {
                Models.User user = db.Users.Find(WebSecurity.CurrentUserId);
                db.Entry(user).Collection(x => x.Rooms).Load();
                return View(db.Users.Find(WebSecurity.CurrentUserId).Rooms.ToList());

               // return View(db.Rooms.ToList());
            }

            return RedirectToAction("Index", "Account");
        }

        //
        // GET: /Room/Create

        public ActionResult Create()
        {
            if (Models.User.IsLoggedIn())
            {
                Room room = new Room();
                room.Participants.Add(db.Users.Find(WebSecurity.CurrentUserId));
                return View(room);
            }

            return RedirectToAction("Index", "Account");
        }

        //
        // POST: /Room/Create

        [HttpPost]
        [ValidateAntiForgeryToken]
        public ActionResult Create(Room room)
        {
            if (ModelState.IsValid)
            {
                room.Participants.Add(db.Users.Find(WebSecurity.CurrentUserId));
                db.Rooms.Add(room);
                db.SaveChanges();
                return RedirectToAction("Index");
            }
            return View(room);
        }

        //
        // GET: /Room/Edit/5

        public ActionResult Edit(int id = 0)
        {
            if (Models.User.IsLoggedIn())
            {
                db.Entry(db.Users.Find(WebSecurity.CurrentUserId)).Collection(x => x.Rooms).Load();
                db.Entry(db.Rooms.Find(id)).Collection(x => x.Participants).Load();
                Room room = db.Rooms.Find(id);

                ViewBag.Users = db.Users.ToList();
               
                if (room == null)
                {
                    return HttpNotFound();
                }
                return View(room);
            }

            return RedirectToAction("Index", "Account");
        }

        //
        // POST: /Room/Edit/5

        [HttpPost]
        [ValidateAntiForgeryToken]
        public ActionResult Edit(Room room)
        {
            if (ModelState.IsValid)
            {
                db.Entry(room).State = EntityState.Modified;
                db.SaveChanges();
                return RedirectToAction("Index");
            }
            return View(room);
        }

        [HttpPost]
        public ActionResult AddParticipant(int RoomId, int participant = -1)
        {
            db.Entry(db.Rooms.Find(RoomId)).Collection(x => x.Participants).Load();
            if(participant >= 0)
            {
                Models.User user = db.Users.Find(participant);
                Room room = db.Rooms.Find(RoomId);

                if (!room.Participants.Any(p => p.UserId == participant))
                {
                    room.Participants.Add(user);
                    db.Entry(room).State = EntityState.Modified;
                    db.SaveChanges();
                }
            }
            return Redirect(Request.UrlReferrer.ToString());
        }

        //[HttpPost]
        public ActionResult DeleteParticipant(int RoomId, int participant)
        {
            db.Entry(db.Rooms.Find(RoomId)).Collection(x => x.Participants).Load();
            if (participant >= 0)
            {
                Models.User user = db.Users.Find(participant);
                Room room = db.Rooms.Find(RoomId);

                if (room.Participants.Any(p => p.UserId == participant))
                {
                    room.Participants.Remove(user);
                    db.Entry(room).State = EntityState.Modified;
                    db.SaveChanges();
                }
            }
            return Redirect(Request.UrlReferrer.ToString());
        }

        //
        // GET: /Room/Delete/5

        public ActionResult Delete(int id = 0)
        {
            if (Models.User.IsLoggedIn())
            {
                Room room = db.Rooms.Find(id);
                if (room == null)
                {
                    return HttpNotFound();
                }
                return View(room);
            }

            return RedirectToAction("Index", "Account");
        }

        //
        // POST: /Room/Delete/5

        [HttpPost, ActionName("Delete")]
        [ValidateAntiForgeryToken]
        public ActionResult DeleteConfirmed(int id)
        {
            Room room = db.Rooms.Find(id);
            db.Rooms.Remove(room);
            db.SaveChanges();
            return RedirectToAction("Index");
        }

        protected override void Dispose(bool disposing)
        {
            db.Dispose();
            base.Dispose(disposing);
        }
    }
}
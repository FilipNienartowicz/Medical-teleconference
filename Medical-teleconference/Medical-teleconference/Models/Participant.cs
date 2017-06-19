﻿using System;
using System.Collections.Generic;
using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;
using System.Data.Entity;
using System.Drawing;
using System.Linq;
using System.Web;

namespace Medical_teleconference.Models
{
    [Table("Participants")]
    public class Participant : DbContext
    {
        [Key]
        [DatabaseGeneratedAttribute(DatabaseGeneratedOption.Identity)]
        public int ParticipantId { get; set; }
        public int RoomId { get; set; }
        public int UserId { get; set; }
    }
}